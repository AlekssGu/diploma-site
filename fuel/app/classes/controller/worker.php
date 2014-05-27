<?php
/**
 * IS Pilsētas ūdens
 *
 * @package    udens
 * @version    0
 * @author     Aleksandrs Gusevs
 * @link       http://udens.agusevs.com
 */

/**
 * Darbinieka darbību kontrolieris
 */
class Controller_Worker extends Controller_Template
{
    /*
     * Ja kāds nevēlams cilvēks vēlas lauzt sistēmu ievadot nepareizu adresi, sūtam uz galveno lapu
     */
    public function action_worker() 
    {
        Response::redirect('/');
    }
    
    /**
     * Nodaļa: 3.3.3.2.	Klientu saraksta apskatīšana (darbinieks)
     * Identifikators: CLN_SHOW_INFO
     *
     * Ļauj darbiniekam apskatīt klientu sarakstu
     * 
     */
    public function action_clients() 
    {
        //Tikai pieslēgušies darbinieku drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
            
        //Padodamie dati skatam
        $data = array();

        //Klientu un to datu saraksts
        $query_clients = DB::select(array('users.id','user_id'),
                                    'users.*',
                                    'persons.*')
                        ->from('users')
                        ->join('persons')->on('persons.id','=','users.person_id')
                        ->where('users.group','=',1); //Klients

        $data['clients'] = $query_clients->as_object()->execute()->as_array();

        $this -> template -> title = 'Klientu pārvaldība - IS Pilsētas ūdens';
        $this -> template -> content = View::forge('worker/clients',$data);        
    }
    
    /**
     * Nodaļa: 3.3.3.1.	Klienta informācijas apskatīšana (klients)
     * Identifikators: CLN_SHOW_ALL
     *
     * Ļauj darbiniekam apskatīt klienta informāciju
     * 
     */
    public function action_load_client($id = null) 
    {
        //Tikai pieslēgušies darbinieku drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        //Ja ir GET metode
        if(Input::method())
        {
            //Ja padotais id nav tukšs
            if($id != '')
            {
                //Masīvs datu nosūtīšanai priekš skata
                $data = array();
                
                //Atrodam klienta personas datus
                $query_clients = DB::select(array('users.id','user_id'),
                                                'users.*',
                                                'persons.*')
                                ->from('users')
                                ->join('persons')->on('persons.id','=','users.person_id')
                                ->where('users.id','=',$id); //Klients
                
                //Izmanto skatu, atrod klienta objektus
                $query_objects = DB::select('*')
                                ->from('client_objects')
                                ->where('client_objects.client_id','=',$id)
                                ->and_where('client_objects.is_deleted','=','N'); //Klients
                
                //Atrod klienta vēsturi un sakārto dilstoši
                $query_cln_history = DB::select('*')
                                    ->from('client_histories')
                                    ->where('client_histories.client_id','=',$id) //Klients
                                    ->order_by('client_histories.created_at','DESC');
                
                $query_cities = DB::select('*')
                                    ->from('cities');

                //Skatam padodamie klientu dati (izpilda iepriekšizveidotos vaicājumus)
                $data['client_data'] = $query_clients->as_object()->execute()->as_array();
                $data['client_objects'] = $query_objects->as_object()->execute()->as_array();
                $data['client_histories'] = $query_cln_history->as_object()->execute()->as_array();
                $data['cities'] = $query_cities->as_object()->execute()->as_array();
                
                return View::forge('worker/client_data', $data);
            }
        }
        else return \NULL;
    }
    
    /**
     * Nodaļa: 3.3.3.6.	Klienta objekta datu ielāde (darbinieks)
     * Identifikators: CLN_LOAD_OBJ_DATA
     *
     * Ļauj darbiniekam ielādēt informāciju par izvēlētā klienta objektu
     * 
     */
    public function action_load_object_data($id = null) 
    {
        //Tikai pieslēgušies darbinieku drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
	
        //Ja ir GET metode
        if(Input::method())
        {
            //Ja padotais id nav tukšs
            if($id != '')
            {
                //Masīvs datu nosūtīšanai priekš skata
                $data = array();
                
                $query_obj_services = DB::select('*')
                                        ->from('all_obj_services')
                                        ->where('all_obj_services.object_id','=',$id) //Objekts
                                        ->and_where('all_obj_services.is_active','=','Y');
                
                $query_obj_meters = DB::select('*')
                                        ->from('meters')
                                        ->where('meters.object_id','=',$id); //Klients
 
                $query_services = DB::select('*')
                                        ->from('services');
                
                //Skatam padodamie klientu dati (izpilda iepriekšizveidotos vaicājumus)
                $data['obj_services'] = $query_obj_services->as_object()->execute()->as_array();
                $data['services'] = $query_services->as_object()->execute()->as_array();
                $data['object_id'] = $id;
                //$data['meters'] = $query_obj_meters->as_object()->execute()->as_array();
                
                return View::forge('worker/object_data', $data);
            }
            
        }
    }
    
    /**
     * Nodaļa: 3.3.3.7.	Klienta objekta dzēšana (darbinieks)
     * Identifikators: CLN_DELETE_OBJECT
     *
     * Ļauj darbiniekam dzēst klienta objektu
     * 
     */
    public function action_delete_object($object_id = null)
    {
        //Tikai pieslēgušies darbinieku drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        if($object_id != '')
        {           
            //Atrod objektu, kuru dzēst
            $object = Model_Object::find($object_id);
            $object -> is_deleted = 'Y';
            
            $query_service_id = DB::select('user_services.id')
                               -> from ('user_services')
                               -> where('obj_id','=',$object_id)
                               -> and_where('is_active','=','Y');
            $service_id = $query_service_id -> as_object() -> execute() -> as_array();
            
            if($service_id)
            {
                $service = Model_User_Service::find($service_id[0]);
                $service -> is_active = 'N';
                
                if($object->save() && $service -> save())
                {
                    Controller_Client::cre_cln_history($object->client_id, 'Dzēsts pakalpojums');
                    Controller_Client::cre_cln_history($object->client_id, 'Dzēsts objekts');

                    Session::set_flash('success','Objekts ir izdzēsts!');
                    Response::redirect_back(); 
                }   
                else
                {
                    Session::set_flash('error','Objekts nav izdzēsts!');

                    $header = new Response();
                    $header -> set_status(301);
                    Response::redirect_back();
                }
            }
            else
            {
                if($object->save())
                {
                    Controller_Client::cre_cln_history($object->client_id, 'Dzēsts objekts');

                    Session::set_flash('success','Objekts ir izdzēsts!');
                    Response::redirect_back(); 
                }
                else
                {
                    Session::set_flash('error','Objekts nav izdzēsts!');

                    $header = new Response();
                    $header -> set_status(301);
                    Response::redirect_back();
                }
            }
        }
        else
        {
            Session::set_flash('error','Objekts nav izdzēsts!');
            
            $header = new Response();
            $header -> set_status(301);
            Response::redirect_back();
        }
    }
    
        /**
         * Nodaļa: 3.3.3.10.	Klienta objekta pievienošana (darbinieks)
         * Identifikators: CLN_CREATE_OBJECT
         *
         * Darbinieks var pievienot klientam jaunu objektu
         * 
         */
        public function action_add_object()
        {
            //Ja ir autorizējies un lietotājs ir darbinieks
            if(Auth::check() && Auth::member(50))
            {
                if(Input::method()=='POST' && Security::check_token())
                {
                    //Izveido jaunu adresi 
                    $new_address = new Model_Address();
                    $new_address -> client_id = Input::post('client_id');
                    $new_address -> city_id = Input::post('city_id');
                    $new_address -> street = Input::post('street');
                    $new_address -> house = Input::post('house');
                    $new_address -> flat = Input::post('flat');
                    $new_address -> district = Input::post('district');
                    $new_address -> post_code = Input::post('post_code');
                    $new_address -> addr_type = 'O';
                    
                    if($new_address -> save())
                    {
                        //Ja adrese izveidota, tad izveido jaunu objektu un piesaista izveidoto adresi
                        $new_object = new Model_Object();
                        $new_object -> client_id = Input::post('client_id');
                        $new_object -> address_id = $new_address->id;
                        $new_object -> name = Input::post('name');
                        $new_object -> notes = Input::post('notes');
                        $new_object -> is_deleted = 'N';
                        
                        if($new_object -> save())
                        {
                            //Ja objekts izveidots, tad saglabā to klienta vēsturē un paziņo par to lietotājam
                            Controller_Client::cre_cln_history(Input::post('client_id'),'Piesaistīts objekts');
                            
                            Session::set_flash('success','Objekts pievienots!');
                            Response::redirect('/darbinieks/klienti');
                        }
                        else
                        {
                            //Ja netika izveidots objekts, tad dzēš iepriekš izveidoto adresi
                            $rollback = Model_Address::find($new_address->id);
                            $rollback->delete();
                            
                            Session::set_flash('error','Neveiksme! Nebija iespējams izveidot jaunu objektu.');
                            Response::redirect('/darbinieks/klienti');
                        }
                    }
                    else
                    {
                        Session::set_flash('error','Neveiksme! Nebija iespējams izveidot jaunu adresi.');
                        Response::redirect('/darbinieks/klienti');
                    }
                    
                }
                else //Nav POST dati vai CSRF tokens
                {
                    Response::redirect('/');
                }
            }
            else //Nav autorizējies/tiesību
            {
                Response::redirect('/');
            }
        }
    
    /**
     * Nodaļa: 3.3.4.6.	Objektam piesaistītā pakalpojuma rediģēšana (darbinieks)
     * Identifikators: SRV_EDIT_SERVICE
     *
     * Darbinieks var rediģēt klientam piesaistītā objekta pakalpojumus.
     * 
     */
    public function action_modify_service() 
    {
        //Tikai pieslēgušies darbinieku drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        //Ja ir POST metode
        if(Input::method()=='POST')
        {
            //Ja labo pakalpojuma datumu "no"
            if(Input::post('name') == 'service_from')
            {
                $service_object = Model_User_Service::find('all', array(
                    'where' => array(
                        array('obj_id', Input::post('object_id')),
                        array('id', Input::post('pk')),
                        array('is_active','=','Y')
                    )
                ));
                
                foreach($service_object as $srv_obj) 
                {
                    $srv_obj_id = $srv_obj -> id;
                }
                
                $service = Model_User_Service::find($srv_obj_id);
                $new_date = date_format(date_create(Input::post('value')),'Y-m-d');
                
                // Ja jaunais datums ir mazāks vai vienāds par date_to, tad atgriežam 
                if($new_date > $service -> date_to)
                {
                    return false;
                }
                        
                $service -> date_from = $new_date;
                
                return $service -> save();
            }
            // Ja labo pakalpojuma datumu "līdz"
            else if (Input::post('name') == 'service_to')
            {
                $service_object = Model_User_Service::find('all', array(
                    'where' => array(
                        array('obj_id', Input::post('object_id')),
                        array('id', Input::post('pk')),
                        array('is_active','=','Y')
                    )
                ));
                
                foreach($service_object as $srv_obj) 
                {
                    $srv_obj_id = $srv_obj -> id;
                }
                
                $service = Model_User_Service::find($srv_obj_id);
                $new_date = date_format(date_create(Input::post('value')),'Y-m-d');
                
                if($new_date < $service -> date_from)
                {
                    return false;
                }
                    
                $service -> date_to = $new_date;
                
                return $service -> save();
            }
            else return false;
            
        }
        else return false;
    }
    
    /**
     * Nodaļa: 3.3.4.10.	Pakalpojuma piesaistīšana klientam (darbinieks)
     * Identifikators: SRV_ADD_CLIENT
     *
     * Uzņēmuma darbinieks var piesaistīt pakalpojumu klienta objektam.
     * 
     */
    public function action_add_service()
    {
        //Tikai pieslēgušies darbinieku drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        $new_obj_srv = new Model_User_Service();
        
        $new_obj_srv -> srv_id = Input::post('service');
        $new_obj_srv -> obj_id = Input::post('object_id');
        $new_obj_srv -> date_from = Input::post('date_from');
        $new_obj_srv -> date_to = Input::post('date_to');
        $new_obj_srv -> is_active = 'Y';
        
        //Ja izdevies pievienot, tad saglabājam to klienta vēsturē
        if($new_obj_srv->save())
        {
            $user_id = Model_Object::find(Input::post('object_id'))->client_id;
            Controller_Client::cre_cln_history($user_id,'Piesaistīts pakalpojums');
        }
        
        return Format::forge(array('id',$new_obj_srv->id))->to_json();
    }
    
    /**
     * Nodaļa: 3.3.4.10.	Pakalpojuma piesaistīšana klientam (darbinieks)
     * Identifikators: SRV_ADD_CLIENT
     *
     * Uzņēmuma darbinieks var piesaistīt pakalpojumu klienta objektam.
     * 
     */
    public function action_deactivate_service()
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
                $service_object = Model_User_Service::find('all', array(
                    'where' => array(
                        array('obj_id', Input::post('object_id')),
                        array('srv_id', Input::post('service_id')),
                        array('is_active','=','Y')
                    )
                ));
                
                foreach($service_object as $srv_obj) 
                {
                    $srv_obj_id = $srv_obj -> id;
                }
                
                $service = Model_User_Service::find($srv_obj_id);
                $service -> is_active = 'N';
                
                //Ja izdevies atslēgt, tad saglabājam to klienta vēsturē
                if($service->save())
                {
                    $meter_object = Model_Meter::find_by('service_id',Input::post('service_id'));
                    foreach($meter_object as $meter)
                    {
                        $meter_id = $meter -> id;
                        $delete_this = Model_Meter::find($meter_id);
                        $delete_this -> delete();
                    }
                    
                    $user_id = Model_Object::find(Input::post('object_id'))->client_id;
                    Controller_Client::cre_cln_history($user_id,'Atslēgts pakalpojums');
                    return true;
                }
                else return false;
    }
    
        /**
         * Nodaļa: 3.3.2.7.	Skaitītāja piesaistīšana objektam (darbinieks)
         * Identifikators: MTR_ADD_SERVICE
         *
         * Darbinieks var piesaistīt skaitītāju klienta pakalpojumam.
         * 
         */
        public function action_add_meter()
        {
            if(Auth::check() && Auth::member(50))
            {
                if(Input::method()=='POST' && Security::check_token())
                {
                    $exists_qnumber = DB::select('*')
                            ->from('meters')
                            ->join('user_services')->on('user_services.id','=','meters.service_id')
                            ->where('meter_number','=',Input::post('number'))
                            ->and_where('user_services.is_active','=','Y')
                            ->limit(1)
                            ->execute();
                    //Ja eksistē skaitītājs ar ievadīto numuru - neļaujam to pievienot.
                    if(count($exists_qnumber) > 0)
                    {
                        Session::set_flash('error','Neveiksme! Skaitītājs ar šādu numuru jau eksistē!');
                        Response::redirect('/darbinieks/klienti/apskatit-pakalpojumu/'.Input::post('object_id').'/'.Input::post('service_id'));
                    }
                    
                    //Vai "datums no" ir lielāks par "datums līdz"
                    if(Date::forge(strtotime(Input::post('date_from')))->format('%Y-%m-%d') > Date::forge(strtotime(Input::post('date_to')))->format('%Y-%m-%d'))
                    {
                        Session::set_flash('error','Neveiksme! Laukam "Datums no" jābūt mazākam par lauku "Datums līdz"!');
                        Response::redirect('/darbinieks/klienti/apskatit-pakalpojumu/'.Input::post('object_id').'/'.Input::post('service_id'));
                    }
                    
                    $new_meter = new Model_Meter();
                    $new_meter -> service_id = Input::post('service_id');
                    $new_meter -> date_from = Date::forge(strtotime(Input::post('date_from')))->format('%Y-%m-%d');
                    $new_meter -> date_to = Date::forge(strtotime(Input::post('date_to')))->format('%Y-%m-%d');
                    $new_meter -> meter_type = Input::post('meter_type');
                    $new_meter -> water_type = Input::post('water_type');
                    $new_meter -> worker_id = 1;
                    $new_meter -> meter_number = Input::post('number');
                    $new_meter -> meter_model = 'nav zināms';
                    $new_meter -> meter_lead = Input::post('lead');
                    
                    if($new_meter -> save())
                    {
                        $new_last_reading = new Model_Reading();
                        $new_last_reading -> meter_id = $new_meter->id;
                        $new_last_reading -> lead = Input::post('lead');
                        $new_last_reading -> date_taken = Date::forge(strtotime(Input::post('date_from')))->format('%Y-%m-%d');
                        $new_last_reading -> period = 'Sākotnējais';
                        $new_last_reading -> status = 'Sākotnējais';
                        
                        if($new_last_reading -> save())
                        {
                            $user_id = Model_Object::find(Input::post('object_id'))->client_id;
                            Controller_Client::cre_cln_history($user_id, 'Pievienots jauns skaitītājs');
                            
                            Session::set_flash('success','Skaitītājs pievienots!');
                            Response::redirect('/darbinieks/klienti/apskatit-pakalpojumu/'.Input::post('object_id').'/'.Input::post('service_id'));   
                        }
                        else
                        {
                            $delete = Model_Meter::find($new_meter->id);
                            $delete -> delete();
                            
                            Session::set_flash('error','Neizdevās pievienot skaitītāju!');
                            Response::redirect('/darbinieks/klienti/apskatit-pakalpojumu/'.Input::post('object_id').'/'.Input::post('service_id'));
                        }
                    }
                    else
                    {
                        Session::set_flash('error','Neveiksme! Neizdevās pievienot skaitītāju!');
                        Response::redirect('/darbinieks/klienti/apskatit-pakalpojumu/'.Input::post('object_id').'/'.Input::post('service_id'));
                    }
                }
                else
                {
                    Response::redirect('/darbinieks/klienti/apskatit-pakalpojumu/'.Input::post('object_id').'/'.Input::post('service_id'));
                }
            }
            else
            {
                Response::redirect('/');
            }
        }
    
    /**
     * Nodaļa: 3.3.2.8.	Skaitītāja labošana (darbinieks)
     * Identifikators: MTR_EDIT_INFO
     *
     * Darbinieks var labot klienta objekta skaitītāja informāciju
     * 
     */
    public function action_edit_meter()
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        if(Input::method() == 'POST')
        {
            $meter_id = Input::post('pk');
            $meter = Model_Meter::find($meter_id);
            
            if(!empty($meter))
            {
                if(Input::post('action') == 'meter_number')
                {
                    $exists = DB::select()
                                ->from('meters')
                                ->where('meters.meter_number','=',Input::post('value'))
                                ->as_object()
                                ->execute()
                                ->as_array();
                    
                    if($exists) return false;
                    $meter -> meter_number = Input::post('value');
                }
                else if(Input::post('action') == 'date_from')
                {
                    // Datumu pārbaude
                    if($meter -> date_to < date_format(date_create(Input::post('value')),'Y-m-d')) return false;
                    $meter -> date_from = date_format(date_create(Input::post('value')),'Y-m-d');
                }
                else if(Input::post('action') == 'date_to')
                {
                    // Datumu pārbaude
                    if($meter -> date_from > date_format(date_create(Input::post('value')),'Y-m-d')) return false;
                    $meter -> date_to = date_format(date_create(Input::post('value')),'Y-m-d');
                }
                else return false;
            }
            else return false;
            
            if($meter -> save()) return true;
            else return false;
        }
        else return false;
        
    }
    
    /**
     * Nodaļa: 3.3.2.9.	Skaitītāja dzēšana (darbinieks)
     * Identifikators: MTR_DELETE
     *
     * Darbinieks var izdzēst klienta objekta skaitītāju.
     * 
     */
    public function action_remove_meter($service_id = null)
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
         
        $meter_object = Model_Meter::find_by('service_id', $service_id);
        $user_srv = Model_User_Service::find($service_id);
        $object = Model_Object::find($user_srv->obj_id);
        
        foreach($meter_object as $meter_data)
        {
            $meter_id = $meter_data -> id;
        }
        
        $meter = Model_Meter::find($meter_id);
        
        //Izdzēš skaitītāja rādījumus pirms dzēst pašu skaitītāju
        $delete_readings = DB::delete('readings')->where('meter_id','=',$meter_id)->execute();
        
        if($meter -> delete())
        {
            //Ja skaitītājs ir dzēsts, tad saglabā to vēsturē un sūta darbinieku atpakaļ
            Controller_Client::cre_cln_history($object->client_id,'Noņemts skaitītājs nr.' . $meter->meter_number);
            Session::set_flash('success','Skaitītājs veiksmīgi noņemts!');
            Response::redirect_back();
        }
        else
        {
            Session::set_flash('error','Kļūda! Skaitītājs nav noņemts.');
            Response::redirect_back();
        }
        
    }
    
    /**
     * Nodaļa: 3.3.3.10.	Visu klientu ievadīto datu apskatīšana (darbinieks)
     * Identifikators: CLN_ENTERED_DATA_LIST
     *
     * Darbinieks var apskatīt klientu ievadītos datus saraksta veidā
     * 
     */
    public function action_all_entered_data()
    {
        //Datu masīvs skatam
        $data = array();
        
        //Visi iesniegtie rādījumi izņemot sākotnējos
        $query_last_readings = DB::select('*')
                            ->from('last_readings')
                            ->where('status','!=','Sākotnējais')
                            ->and_where('status','!=','Atgriezts')
                            ->and_where('status','!=','Apstiprināts');
        $last_readings = $query_last_readings -> as_object() -> execute() -> as_array();

        //Iesniegtie pakalpojumi
        $query_usr_srv_req = DB::select('*')
                            ->from('all_usr_requests')
                            ->where('status','!=','Atteikts')
                            ->and_where('status','!=','Apstiprināts');
        $service_requests = $query_usr_srv_req -> as_object() -> execute() -> as_array();      
        
        //Iesniegtās avārijas
        $emergencies = DB::select()->from('emergencies')->as_object()->execute()->as_array();
        
        //Iesniegtie jautājumi
        $usr_questions = DB::select('questions.*',
                                    'topics.*',
                                    'users.*',
                                    'persons.*',
                                    array('questions.id','quest_id'),
                                    array('questions.email','quest_email'),
                                    array('questions.created_at','quest_created'))
                        ->from('questions')
                        ->join('topics')->on('topics.id','=','questions.topic_id')
                        ->join('users','LEFT OUTER')->on('questions.user_id','=','users.id')
                        ->join('persons','LEFT OUTER')->on('persons.id','=','users.person_id')
                        ->order_by('quest_created','ASC')
                        ->as_object()
                        ->execute()
                        ->as_array();
        
        //Sagatavo datus skatam
        $data['readings'] = $last_readings;
        $data['services'] = $service_requests;
        $data['emergencies'] = $emergencies;
        $data['usr_questions'] = $usr_questions;
        
        $this -> template -> title = 'Iesniegtie dati - IS Pilsētas ūdens';
        $this -> template -> content = View::forge('worker/all_entered_data', $data);
    }
    
    /**
     * Nodaļa: 3.3.5.13.	Iesniegto jautājumu dzēšana (darbinieks)
     * Identifikators: IS_STC_DEL_QUESTION
     *
     * Darbinieks var izdzēst iesniegtos jautājumus
     * 
     */
    public function action_delete_question($quest_id = null)
    {
        //Ja ir autorizējies un darbinieka loma
        if(Auth::check() && Auth::member(50))
        {
            is_null($quest_id) and Response::redirect('/');
            
            //Atrod jautājumu
            $question = Model_Question::find($quest_id);
            
            //Izdzēš
            if($question -> delete()) 
            {
                Session::set_flash('success','Jautājums izdzēsts!');
                Response::redirect_back();
            }
            else
            {
                Session::set_flash('error','Jautājums netika izdzēsts!');
                Response::redirect_back();
            }
        }
        else
        {
            Response::redirect('/');
        }
    }
    
    /**
     * Nodaļa: 3.3.2.11.	Skaitītāja rādījuma atgriešana (darbinieks)
     * Identifikators: MTR_REJECT_RDN
     *
     * Darbinieks var apstiprināt iesniegto skaitītāja rādījumu. 
     * Kad tiek apstiprināts skaitītāja rādījums, tam uzstādās statuss „Atgriezts” 
     * un klients to redz kā pēdējo iesniegto rādījumu.
     * 
     */
    public function action_return_reading()
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        //Ja ir POST metode
        if(Input::method()=='POST')
        {
            if(Input::post('reading_id') == '')
            {
                Session::set_flash('error','Skaitītāja rādījums netika atgriezts!');
                Response::redirect_back();
            }
            else
            {
                if(Input::post('notes')=='')
                {
                    Session::set_flash('error','Nav norādīts pamatojums, kādēļ tiek atgriezts rādījums!');
                    Response::redirect_back();
                }
                
                $reading = Model_Reading::find(Input::post('reading_id'));
                $reading -> status = 'Atgriezts';
                $reading -> notes = Input::post('notes');
                
                if($reading -> save())
                {
                    Controller_Client::cre_cln_history(Input::post('client_id'),'Atgriezts skaitītāja rādījums!');
                    Session::set_flash('success','Skaitītāja rādījums atgriezts!');
                    Response::redirect_back();
                }
                else
                {
                    Session::set_flash('error','Skaitītāja rādījums netika atgriezts!');
                    Response::redirect_back();
                }
            }
        }
    }
    
    /**
     * Nodaļa: 3.3.2.10.	Skaitītāja rādījuma apstiprināšana (darbinieks)
     * Identifikators: MTR_ACCEPT_RDN
     *
     * Darbinieks var apstiprināt iesniegto skaitītāja rādījumu. 
     * Kad tiek apstiprināts skaitītāja rādījums, tam uzstādās statuss „Apstiprināts” 
     * un klients to redz kā pēdējo iesniegto rādījumu.
     * 
     */
    public function action_accept_reading()
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        $reading_id = $this -> param('reading_id');
        $client_id = $this -> param('client_id');
        
        //Ja ir GET metode
        if(Input::method())
        {
            if($reading_id == '')
            {
                Session::set_flash('error','Skaitītāja rādījums netika apstiprināts!');
                Response::redirect_back();
            }
            else
            {
                
                $reading = Model_Reading::find($reading_id);
                $reading -> status = 'Apstiprināts';
                $reading -> notes = 'Apstiprināts klientu daļā';
                
                if($reading -> save())
                {
                    Controller_Client::cre_cln_history($client_id,'Apstiprināts skaitītāja rādījums!');
                    Session::set_flash('success','Skaitītāja rādījums apstiprināts!');
                    Response::redirect_back();
                }
                else
                {
                    Session::set_flash('error','Skaitītāja rādījums netika apstiprināts!');
                    Response::redirect_back();
                }
            }
        }
    }
    
    /**
     * Nodaļa: 3.3.3.6.	Klienta informācijas rediģēšana (darbinieks)
     * Identifikators: CLN_EDIT_ALL_INFO
     *
     * Sistēmas darbinieki, administratori var rediģēt esoša klienta informāciju.
     * 
     */
    public function action_change_client_data() 
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        $saved = false;
        
        //Ja ir saņemti dati
        if(Input::method()=='POST')
        {
            //Ja maina klienta numuru
            if(Input::post('name') == 'cln_number')
            {
                $client = Model_User::find(Input::post('pk'));
                $person = Model_Person::find($client->person_id);
                
                $exists = DB::select()
                        ->from('users')
                        ->where('users.username','=',Input::post('value'))
                        ->as_object()
                        ->execute()
                        ->as_array();
                
                if($exists) $saved = false;
                else 
                {

                    $person -> client_number = Input::post('value');
                    $client -> username = Input::post('value');

                    $saved = $person -> save() && $client -> save();
                
                    Controller_Client::cre_cln_history(Input::post('pk'), 'Labots klienta numurs');
                }
            }
            //Ja maina personas tipu
            else if(Input::post('name') == 'person_type')
            {
                $client = Model_User::find(Input::post('pk'));
                $person = Model_Person::find($client -> person_id);
                $person -> person_type = Input::post('value');
                $saved = $person -> save();
                
                Controller_Client::cre_cln_history(Input::post('pk'), 'Labots personas tips');
            }
            //Ja maina klienta vārdu
            else if(Input::post('name') == 'cln_name')
            {
                $client = Model_User::find(Input::post('pk'));
                $person = Model_Person::find($client -> person_id);
                $person -> name = Input::post('value');
                $saved = $person -> save();
                
                Controller_Client::cre_cln_history(Input::post('pk'), 'Labots klienta vārds');
            }
            //Ja maina klienta uzvārdu
            else if(Input::post('name') == 'cln_surname')
            {
                $client = Model_User::find(Input::post('pk'));
                $person = Model_Person::find($client -> person_id);
                $person -> surname = Input::post('value');
                $saved = $person -> save();
                
                Controller_Client::cre_cln_history(Input::post('pk'), 'Labots klienta uzvārds');
            }
            //Ja maina klienta personas kodu
            else if(Input::post('name') == 'client_pk')
            {
                $exists = DB::select()
                        ->from('persons')
                        ->where('persons.person_code','=',Input::post('value'))
                        ->as_object()
                        ->execute()
                        ->as_array();
                
                if($exists) $saved = false;
                else 
                {

                    $client = Model_User::find(Input::post('pk'));
                    $person = Model_Person::find($client -> person_id);
                    $person -> person_code = Input::post('value');
                    $saved = $person -> save();

                    Controller_Client::cre_cln_history(Input::post('pk'), 'Labots klienta personas kods');
                }
            }
            //Ja maina klienta telefona numuru
            else if(Input::post('name') == 'client_phone')
            {
                $client = Model_User::find(Input::post('pk'));
                $person = Model_Person::find($client -> person_id);
                $person -> mobile_phone = Input::post('value');
                $saved = $person -> save();
                
                Controller_Client::cre_cln_history(Input::post('pk'), 'Labots klienta telefona numurs');
            }
            //Ja maina klienta e-pastu
            else if(Input::post('name') == 'client_email')
            {
                //E-pastam jābūt unikālam
                $check_existing = Model_User::find_by_email(Input::post('value'));
                if(!empty($check_existing)) $saved = false;
                else 
                {
                    $client = Model_User::find(Input::post('pk'));
                    $client -> email = Input::post('value');
                    $saved = $client -> save();
                }
            }
            //Ja atver klienta kontu
            else if(Input::post('name') == 'activate')
            {
                $client = Model_User::find(Input::post('pk'));
                $client -> is_active = Input::post('value');
                $saved = $client -> save();
                
                if($saved) 
                {
                    Controller_Client::cre_cln_history(Input::post('pk'), 'Atvērts lietotāja konts');
                    
                    //Atgriež json, ka ir saglabāts
                    $json_string = '{"user_id":' . Input::post('pk') .',"saved":"true"}';
                    return $json_string;
                }
                else return false;
                
            }
            //Ja slēdz lietotāja kontu
            else if(Input::post('name') == 'deactivate')
            {
                $client = Model_User::find(Input::post('pk'));
                $client -> is_active = Input::post('value');
                $saved = $client -> save();
                
                if($saved) 
                {
                    Controller_Client::cre_cln_history(Input::post('pk'), 'Slēgts lietotāja konts');
                    
                    $json_string = '{"user_id":' . Input::post('pk') .',"saved":"true"}';
                    return $json_string;
                }
                else return false;   
            }
            else return false;
        } 
        else return false;
        
        if($saved) return true;
        else return true;
    }
    
    /**
     * Nodaļa: 3.3.4.2.	Pakalpojumu saraksta apskatīšana (darbinieks)
     * Identifikators: SRV_LIST_ALL
     *
     * Uzņēmuma darbinieki var apskatīt piedāvājamo pakalpojumu sarakstu
     * 
     */
    public function action_services()
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        $data = array();
        
        $query_services = DB::select(
                    array('services.id', 'srv_id'),
                    array('codificators.id','cdf_id'),
                    'services.*',
                    'codificators.*')
                ->from('services')
                ->join('codificators')->on('codificators.id','=','services.code_id');
        $services = $query_services -> as_object() -> execute() -> as_array();
        
        $data['services'] = $services;
        
        $this -> template -> title = "Pieejamie pakalpojumi - IS Pilsētas ūdens";
        $this -> template -> content = View::forge('worker/services', $data);
        
    }
    
    /**
     * Nodaļa: 3.3.4.1.	Pakalpojuma izveidošana (darbinieks)
     * Identifikators: SRV_CREATE
     *
     * Uzņēmuma darbinieks var izveidot jaunu pakalpojumu
     * 
     */
    public function action_create_service()
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        if(Input::method() == 'POST' && Security::check_token())
        {
            $query_existing_codes = DB::select('*')
                    ->from('codificators')
                    ->where('codificators.code','=',Input::post('code'));
            $exists_code = $query_existing_codes -> as_object() -> execute() -> as_array();
            
            if(!empty($exists_code))
            {
                Session::set_flash('error','Pakalpojuma kods jau eksistē datubāzē');
                Response::redirect_back();
            }
            
            $new_code = new Model_Codificator();
            $new_code -> code = Input::post('code');
            $new_code -> used_in = 'Klientu daļa';
            $new_code -> comments = Input::post('code_notes');
            
            $saved_code = $new_code -> save();
            
            if($saved_code)
            {
                $new_service = new Model_Service();
                $new_service -> code_id = $new_code->id;
                $new_service -> name = Input::post('service_name');
                $new_service -> description = Input::post('service_notes');
                
                if($new_service -> save())
                {
                    Session::set_flash('success','Pakalpojums veiksmīgi pievienots');
                    Response::redirect_back();
                }
                else
                {
                    $delete_code = Model_Codificator::find($new_code->id);
                    $delete_code -> delete();
                    
                    Session::set_flash('error','Pakalpojums netika pievienots');
                    Response::redirect_back();
                }
            }
            else
            {
                Session::set_flash('error','Pakalpojums netika pievienots');
                Response::redirect_back();
            }
        }
        else
        {
            Session::set_flash('error','Netika saņemti korekti dati');
            Response::redirect_back();
        }
    }
    
    /**
     * Nodaļa: 3.3.4.4.	Pakalpojuma dzēšana (darbinieks)
     * Identifikators: SRV_DELETE
     *
     * Uzņēmuma darbinieks var dzēst pakalpojumu
     * 
     */
    public function action_delete_service($service_id = null)
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        if($service_id != '')
        {
            $delete_service = Model_Service::find($service_id);
            $delete_code = Model_Codificator::find($delete_service->code_id);
            
            //Pārbauda, vai pakalpojums ir piesaistīts un aktīvs kādam klientam
            $query_existing_srv = DB::select('*')
                    ->from('user_services')
                    ->where('user_services.srv_id','=',$service_id)
                    ->and_where('user_services.is_active','=','Y');
            $exists_srv = $query_existing_srv -> as_object() -> execute() -> as_array();
            
            // Ir piesaistīts
            if(count($exists_srv) > 0)
            {
                Session::set_flash('error','Pakalpojumu nedrīkst dzēst, jo tas ir piesaistīts klientam!');
                Response::redirect_back();
            }
            
            if($delete_code -> delete() && $delete_service -> delete())
            {
                Session::set_flash('success','Pakalpojums veiksmīgi izdzēsts');
                Response::redirect_back();
            }
            else
            {
                Session::set_flash('error','Pakalpojums netika izdzēsts');
                Response::redirect_back();
            }
        }
    }
    
    /**
     * Nodaļa: 3.3.4.3.	Pakalpojuma rediģēšana (darbinieks)
     * Identifikators: SRV_EDIT
     *
     * Uzņēmuma darbinieki var rediģēt pakalpojumu.
     * 
     */
    public function action_edit_service()
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        if(Input::method() == 'POST')
        {
            //Ja maina kodu
            if(Input::post('action') == 'code')
            {
                $check_existing = DB::select('*')
                                    ->from('codificators')
                                    ->where('codificators.code','=',Input::post('value'))
                                    ->execute();
                if(count($check_existing) > 0 )
                {
                    return false;
                }
                
                $codificator = Model_Codificator::find(Input::post('pk'));
                $codificator -> code = Input::post('value');
                
                if($codificator->save()) return true;
                else return false;
            }
            //Ja maina pakalpojuma nosaukumu
            else if(Input::post('action') == 'srv_name')
            {
                $service = Model_Service::find(Input::post('pk'));
                $service -> name = Input::post('value');
                
                if($service->save()) return true;
                else return false;
            }
           //Ja maina pakalpojuma aprakstu
            else if(Input::post('action') == 'srv_desc')
            {
                $service = Model_Service::find(Input::post('pk'));
                $service -> description = Input::post('value');
                
                if($service->save()) return true;
                else return false;
            }
            else return false;
        }
        else return false;
    }
    
    /**
     * Nodaļa: 3.3.4.12.	Pakalpojuma pieprasījuma atteikšana (darbinieks)
     * Identifikators: SRV_REJECT_REQUEST
     *
     * Uzņēmuma darbinieks var atteikt pasūtīto klienta pakalpojumu
     * 
     */
    public function action_reject_service_request()
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        if(Input::method() == 'POST' && Security::check_token())
        {
            //Atrod pieprasījuma objektu un uzstāda statusu
            $request = Model_Usr_Service_Request::find(Input::post('pk'));
            $request -> status = 'Atteikts';
            $request -> status_notes = Input::post('status_notes');
            
            //Ja izdevies atteikt, tad parāda paziņojumu par to
            if($request -> save())
            {
                Session::set_flash('success','Pakalpojuma pieprasījums atteikts!');
                Response::redirect_back();
            }
            //Nav izdevies atteikt
            else
            {
                Session::set_flash('error','Neveiksme! Pakalpojuma pieprasījums atteikts!');
                Response::redirect_back();
            }
        }
        //Padoti nepareizi dati - pāradresējam uz sākumlapu
        else
        {
            Response::redirect('/');
        }
    }
    
    /**
     * Nodaļa: 3.3.4.9.	Pakalpojuma pieprasījuma apstiprināšana (darbinieks)
     * Identifikators: SRV_ACCEPT_REQUEST
     *
     * Uzņēmuma darbinieks var apstiprināt pakalpojuma pieprasījumu.
     * 
     */
    public function action_accept_service_request($req_id = null)
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        if(Input::method())
        {
            //Atrod pieprasījuma objektu un uzstāda statusu
            $request = Model_Usr_Service_Request::find($req_id);
            $request -> status = 'Apstiprināts';
            $request -> status_notes = 'Darbinieks ir apstiprinājis pakalpojuma pieprasījumu';
            
            if($request -> usr_srv_id) 
            {
                $usr_service = Model_User_Service::find($request -> usr_srv_id);
                $usr_service -> is_active = 'N';
                
                Controller_Client::cre_cln_history($request -> client_id, 'Apstiprināts pakalpojuma pieprasījums atslēgt pakalpojumu!');
            }
            else
            {
                $usr_service = new Model_User_Service();
                $usr_service -> obj_id = $request -> object_id;
                $usr_service -> srv_id = $request -> service_id;
                $usr_service -> date_from = $request -> date_from;
                $usr_service -> date_to = $request -> date_to;
                $usr_service -> is_active = 'Y';
            }
            
            //Ja izdevies atteikt, tad parāda paziņojumu par to
            if($request -> save() && $usr_service -> save())
            {
                Session::set_flash('success','Pakalpojuma pieprasījums apstiprināts!');
                Response::redirect_back();
            }
            //Nav izdevies atteikt
            else
            {
                Session::set_flash('error','Neveiksme! Pakalpojuma pieprasījums netika apstiprināts!');
                Response::redirect_back();
            }
        }
        //Padoti nepareizi dati - pāradresējam uz sākumlapu
        else
        {
            Response::redirect('/');
        }
    }
    
        /**
	 * Funkcija: 3.3.5.3.	Ziņu lapas attēlošana (viesis, klients, darbinieks)
         * Identifikators: IS_STC_NEWS
	 *
         * Visi lietotāji var skatīt ziņu lapu, kur ir jaunākās ziņas un aprakstītas aktualitātes sakarā ar uzņēmuma darbību. 
	 */
	public function action_news()
	{
            if(Auth::check() && Auth::member(50))
            {
		$data['news'] = Model_News::find('all');
		$this->template->title = "Jaunumi - IS Pilsētas ūdens";
		$this->template->content = View::forge('news/index', $data);
            }
            else
            {
                $this -> template -> title = "Ziņas - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/recent/news',$data);
            }

	}
        
        /**
	 * Funkcija: 3.3.5.4.	Vienas ziņas apskatīšana (darbinieks)
         * Identifikators: IS_STC_NEWS_VIEW_ONE
	 *
         * Uzņēmuma darbinieks apskatīt vienas ziņas informāciju 
	 */
	public function action_view_news($id = null)
	{
            if(Auth::check() && Auth::member(50))
            {
		is_null($id) and Response::redirect('news');

		if ( ! $data['news'] = Model_News::find($id))
		{
			Session::set_flash('error', 'Kļūda! Ziņa netika atrasta.');
			Response::redirect('news');
		}

		$this->template->title = "Izveidotie jaunumi - IS Pilsētas ūdens";
		$this->template->content = View::forge('news/view', $data);
            }
            else
            {
                Response::redirect('/aktuali/jaunumi');
            }

	}

        /**
	 * Funkcija: 3.3.5.5.	Ziņu pievienošana (darbinieks)
         * Identifikators: IS_STC_ADD_NEWS
	 *
         * Uzņēmuma darbinieks var pievienot jaunas ziņas
	 */
	public function action_create_news()
	{
            $data = array();
            $filename = '';
            $filename_sys = '';
            
            if(Auth::check() && Auth::member(50))
            {
                //Ja ir padoti dati 
		if (Input::method() == 'POST' && Security::check_token())
		{
                    $error_message = ""; // Kļūdas ziņojuma daļa
                    $error_count = 0; // Kļūdu skaits
                    $result_message = ""; // Gala paziņojums lietotājam

                    // Ja nav ievadīts ziņas virsraksts
                    if (Input::post('title')=='')
                    {
                        $error_count++;
                        $error_message.='<li>Nav ievadīts ziņas virsraksts</li>'; 
                    }
                    // Ja nav ievadīts ziņas teksts
                    if (Input::post('body')=='')
                    {
                        $error_count++;
                        $error_message.='<li>Nav ievadīts ziņas teksts</li>'; 
                    }
                    // Ja kļūdu skaits ir lielāks par 0, tad izvada paziņojumu un neļauj izveidot ierakstu
                    if($error_count > 0)
                    {
                        Session::set_flash('error','<ul>' . $error_message . '</ul>');
                        Response::redirect('/darbinieks/jaunumi/izveidot');
                    }
                        
                    //Konfigurācija
                    $config = array(
                        'path' => DOCROOT.'/assets/img/news', //Kur saglabāt attēlu
                        'randomize' => true, //pārtaisa faila nosaukumu uz nejaušu simbolu virkni
                        'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png') //Pieļaujamais attēla formāts
                    );

                    //Augšupielādē attēlu
                    Upload::process($config);
                    //Ja ir izdevies
                    if(Upload::is_valid())
                    {
                        //Saglabā to failsistēmā
                        Upload::save();
                        //Pieglabā failu nosaukumus
                        foreach (Upload::get_files() as $file)
                        {
                            $filename = $file['name'];
                            $filename_sys = $file['saved_as'];
                        }
                    }
                            //Izveido jaunu ziņu
                            $news = Model_News::forge(array(
                                    'title' => Input::post('title'),
                                    'body' => Input::post('body'),
                                    'excerpt' => Input::post('excerpt'),
                                    'status' => 'Publisks', //Saglabāt un publicēt
                                    'lang' => 'lv', //Pagaidām tiek realizēta tikai latviešu valoda
                                    'filename' => $filename,
                                    'filename_sys' => $filename_sys,
                                    'file_source' => Input::post('source')
                            ));
                            //Saglabā ziņu
                            if ($news and $news->save())
                            {
                                    Session::set_flash('success', 'Pievienota ziņa "' . $news -> title . '"');
                                    Response::redirect('/darbinieks/jaunumi');
                            }
                            else
                            {
                                    Session::set_flash('error', 'Kļūda! Ziņa netika pievienota!');
                            }
		}

		$this->template->title = "Izveidot jaunu ziņu - IS Pilsētas ūdens";
		$this->template->content = View::forge('news/create',$data);
            }
            else Response::redirect('/aktuali/jaunumi');
	}

         /**
	 * Funkcija: 3.3.5.7.	Ziņu rediģēšana (darbinieks)
         * Identifikators: IS_STC_EDIT_NEWS
	 *
         * Uzņēmuma darbinieks var labot ziņas
	 */
	public function action_edit_news($id = null)
	{
            if(Auth::check() && Auth::member(50))
            {
		is_null($id) and Response::redirect('/darbinieks/jaunumi');

		if ( ! $news = Model_News::find($id))
		{
			Session::set_flash('error', 'Kļūda! Netika atrasta ziņa "' . $news -> title . '"');
			Response::redirect('/darbinieks/jaunumi');
		}
                
                if (Input::method() == 'POST' && Security::check_token())
                {
                    $data = array();
                    
                    $filename = $news -> filename;
                    $filename_sys = $news -> filename_sys;
                    
                    //Ja netiek mainīts virsraksts, tad liek esošo, lai nebūtu tukšums
                    if(Input::post('title') == '') $title = $news -> title;
                    else $title = Input::post('title');
                    //Ja netiek mainīts virsraksts, tad liek esošo, lai nebūtu tukšums
                    if(Input::post('body') == '') $body = $news -> body;
                    else $body = Input::post('body');
                    //Ja netiek mainīts virsraksts, tad liek esošo, lai nebūtu tukšums
                    if(Input::post('excerpt') == '') $excerpt = $news -> excerpt;
                    else $excerpt = Input::post('excerpt');
                    //Ja netiek mainīts virsraksts, tad liek esošo, lai nebūtu tukšums
                    if(Input::post('source') == '') $source = $news -> file_source;
                    else $source = Input::post('source');
                    
                    //Konfigurācija
                    $config = array(
                        'path' => DOCROOT.'/assets/img/news', //kur saglabāt failu
                        'randomize' => true, //pārtaisa attēla nosaukumu uz nejaušu simbolu virkni
                        'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'), //pieļaujamie formāti
                    );

                    //Augšupielādē failus
                    Upload::process($config);
                    //Ja izdevies
                    if(Upload::is_valid())
                    {
                        //Saglabā failus failsistēmā
                        Upload::save();
                        //Pieglabā failu informāciju
                        foreach (Upload::get_files() as $file)
                        {
                            $filename = $file['name'];
                            $filename_sys = $file['saved_as'];
                        }
                    }

                    //Labo ziņu
                    $news->title = $title;
                    $news->body = $body;
                    $news->excerpt = $excerpt;
                    $news->status = 'Publisks';
                    $news->filename = $filename;
                    $news->filename_sys = $filename_sys;
                    $news->file_source = $source;

                    //Saglabā labojumus
                    if($news -> save())
                    {
                        Session::set_flash('success','Ziņa veiksmīgi labota!');
                        Response::redirect('/darbinieks/jaunumi/skatit/' . $id);
                    }
                }

                $this->template->set_global('news', $news, false);

		$this->template->title = "Labot ziņu - IS Pilsētas ūdens";
		$this->template->content = View::forge('news/edit');
            }
            else
            {
                Response::redirect('/aktuali/jaunumi');
            }

	}

         /**
	 * Funkcija: 3.3.5.8.	Ziņu dzēšana (darbinieks)
         * Identifikators: IS_STC_DEL_NEWS
	 *
         * Uzņēmuma darbinieks var dzēst ziņas
	 */
	public function action_delete_news($id = null)
	{
            if(Auth::check() && Auth::member(50))
            {
		is_null($id) and Response::redirect('/darbinieks/jaunumi');

		if ($news = Model_News::find($id))
		{
			$news->delete();
			Session::set_flash('success', 'Dzēsta ziņa "' . $news -> title . '"' );
		}

		else
		{
			Session::set_flash('error', 'Netika atrasta ziņa "' . $news -> title . '"');
		}

		Response::redirect('/darbinieks/jaunumi');
            }
            else
            {
                Response::redirect('/aktuali/jaunumi');
            }

	}
        
         /**
	 * Funkcija: 3.3.5.11.	Bojājuma paziņojuma dzēšana (darbinieks)
         * Identifikators: IS_DELETE_ISSUE
	 *
         * Darbinieki var dzēst bojājumu paziņojumus
	 */
        public function action_delete_issue($issue_id = null)
        {
            //Vai ir autorizējies un ir darbinieks
            if(Auth::check() && Auth::member(50))
            {
		is_null($issue_id) and Response::redirect('/darbinieks/iesniegtie/dati');
                
                //Atrod ierakstu, kuru dzēst
                $delete = Model_Emergency::find($issue_id);
                
                //Ja nav atrasts, tad parāda kļūdas paziņojumu
                if(!$delete)
                {
                    Session::set_flash('error','Sistēmā nav ievadītā bojājuma paziņojuma! Visticamāk, ka tas jau ir dzēsts.');
                    Response::redirect('/darbinieks/iesniegtie/dati');
                }
                
                //Ja izdevies dzēst
                if($delete -> delete())
                {
                    Session::set_flash('success','Bojājuma paziņojums veiksmīgi dzēsts!');
                    Response::redirect('/darbinieks/iesniegtie/dati');
                }
                //Ja nav izdevies dzēst
                else 
                {
                    Session::set_flash('error','Bojājuma paziņojums netika dzēsts!');
                }
            }
            else
            {
                Response::redirect('/pazinot-par-bojajumu');
            }
        }
}
