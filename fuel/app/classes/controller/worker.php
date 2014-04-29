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
    public function action_worker() 
    {
        Response::redirect('/');
    }
    
    public function action_clients() 
    {
        //Tikai pieslēgušies darbinieku drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
            
        $data = array();

        $query_clients = DB::select(array('users.id','user_id'),
                                    'users.*',
                                    'persons.*')
                        ->from('users')
                        ->join('persons')->on('persons.id','=','users.person_id')
                        ->where('users.group','=',1); //Abonents

        $data['clients'] = $query_clients->as_object()->execute()->as_array();

        $this -> template -> title = 'Abonentu pārvaldība - IS Pilsētas ūdens';
        $this -> template -> content = View::forge('worker/clients',$data);        
    }
    
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
                                ->where('users.id','=',$id); //Abonents
                
                //Izmanto skatu, atrod klienta objektus
                $query_objects = DB::select('*')
                                ->from('client_objects')
                                ->where('client_objects.client_id','=',$id)
                                ->and_where('client_objects.is_deleted','=','N'); //Abonents
                
                //Atrod klienta vēsturi un sakārto dilstoši
                $query_cln_history = DB::select('*')
                                    ->from('client_histories')
                                    ->where('client_histories.client_id','=',$id) //Abonents
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
                                        ->where('meters.object_id','=',$id); //Abonents
 
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
            
            $query_service_id = DB::select('*')
                               -> from ('user_services')
                               -> where('obj_id','=',$object_id);
            $service_id = $query_service_id -> as_object() -> execute() -> as_array();
            
            $service = Model_User_Service::find($service_id);
            $service -> is_active = 'N';
            
            Controller_Client::cre_cln_history($object->client_id, 'Dzēsts pakalpojums');
            Controller_Client::cre_cln_history($object->client_id, 'Dzēsts objekts');
            
            $saved_srv = $service -> save();
            $saved_obj = $object -> save();
            
            Response::redirect_back(); 
        }
        else
        {
            $header = new Response();
            $header -> set_status(301);
            Response::redirect_back();
        }
    }
    
    
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
                        array('srv_id', Input::post('pk')),
                        array('is_active','=','Y')
                    )
                ));
                
                foreach($service_object as $srv_obj) 
                {
                    $srv_obj_id = $srv_obj -> id;
                }
                
                $service = Model_User_Service::find($srv_obj_id);
                $service -> date_from = date_format(date_create(Input::post('value')),'Y-m-d');
                
                return $service -> save();
            }
            // Ja labo pakalpojuma datumu "līdz"
            else if (Input::post('name') == 'service_to')
            {
                $service_object = Model_User_Service::find('all', array(
                    'where' => array(
                        array('obj_id', Input::post('object_id')),
                        array('srv_id', Input::post('pk')),
                        array('is_active','=','Y')
                    )
                ));
                
                foreach($service_object as $srv_obj) 
                {
                    $srv_obj_id = $srv_obj -> id;
                }
                
                $service = Model_User_Service::find($srv_obj_id);
                $service -> date_to = date_format(date_create(Input::post('value')),'Y-m-d');
                
                return $service -> save();
            }
            else return false;
            
        }
        else return false;
    }
    
    //piesaista pakalpojumu objektam
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
        
        //Ja izdevies pievienot, tad saglabājam to abonenta vēsturē
        if($new_obj_srv->save())
        {
            $user_id = Model_Object::find(Input::post('object_id'))->client_id;
            Controller_Client::cre_cln_history($user_id,'Piesaistīts pakalpojums');
        }
        
        return Format::forge(array('id',$new_obj_srv->id))->to_json();
    }
    
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
                
                //Ja izdevies atslēgt, tad saglabājam to abonenta vēsturē
                if($service->save())
                {
                    $meter_object = Model_Meter::find_by('service_id',Input::post('service_id'));
                    foreach($meter_object as $meter)
                    {
                        $meter_id = $meter -> id;
                    }
                    $delete_this = Model_Meter::find($meter_id);
                    $delete_this -> delete();
                    
                    $user_id = Model_Object::find(Input::post('object_id'))->client_id;
                    Controller_Client::cre_cln_history($user_id,'Atslēgts pakalpojums');
                    return true;
                }
                else return false;
    }
    
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
            
            if(Input::post('action') == 'meter_number')
            {
                $meter -> meter_number = Input::post('value');
            }
            else if(Input::post('action') == 'date_from')
            {
                $meter -> date_from = Input::post('value');
            }
            else if(Input::post('action') == 'date_to')
            {
                $meter -> date_to = Input::post('value');
            }
            else return false;
            
            if($meter -> save()) return true;
            else return false;
        }
        else return false;
        
    }
    
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
        
    }
    
    public function action_all_entered_data()
    {
        //Datu masīvs skatam
        $data = array();
        
        //Visi iesniegtie rādījumi
        $query_last_readings = DB::select('*')->from('last_readings');
        $last_readings = $query_last_readings -> as_object() -> execute() -> as_array();

        //Iesniegtie pakalpojumi
        $query_usr_srv_req = DB::select('*')->from('usr_service_requests');
        $service_requests = $query_usr_srv_req -> as_object() -> execute() -> as_array();       
        
        //Iesniegtās avārijas
        
        //Sagatavo datus skatam
        $data['readings'] = $last_readings;
        $data['services'] = $service_requests;
        $data['emergencies'] = NULL;
        
        $this -> template -> title = 'Iesniegtie dati - IS Pilsētas ūdens';
        $this -> template -> content = View::forge('worker/all_entered_data', $data);
    }
    
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
                $reading -> notes = 'Apstiprināts abonentu daļā';
                
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
    
    public function action_change_client_data() 
    {
        //Tikai pieslēgušies darbinieki drīkst piekļūt šai lapai
        if(!Auth::check() || !Auth::member(50))
        {
            Response::redirect('/');
        }
        
        $saved = false;
        
        if(Input::method()=='POST')
        {
            
            if(Input::post('name') == 'cln_number')
            {
                $client = Model_User::find(Input::post('pk'));
                $client -> username = Input::post('value');
                $saved = $client -> save();
                
                Controller_Client::cre_cln_history(Input::post('pk'), 'Labots klienta numurs');
            }
            else if(Input::post('name') == 'person_type')
            {
                $client = Model_User::find(Input::post('pk'));
                $person = Model_Person::find($client -> person_id);
                $person -> person_type = Input::post('value');
                $saved = $person -> save();
                
                Controller_Client::cre_cln_history(Input::post('pk'), 'Labots personas tips');
            }
            else if(Input::post('name') == 'cln_name')
            {
                $client = Model_User::find(Input::post('pk'));
                $person = Model_Person::find($client -> person_id);
                $person -> name = Input::post('value');
                $saved = $person -> save();
                
                Controller_Client::cre_cln_history(Input::post('pk'), 'Labots klienta vārds');
            }
            else if(Input::post('name') == 'cln_surname')
            {
                $client = Model_User::find(Input::post('pk'));
                $person = Model_Person::find($client -> person_id);
                $person -> surname = Input::post('value');
                $saved = $person -> save();
                
                Controller_Client::cre_cln_history(Input::post('pk'), 'Labots klienta uzvārds');
            }
            else if(Input::post('name') == 'client_pk')
            {
                $client = Model_User::find(Input::post('pk'));
                $person = Model_Person::find($client -> person_id);
                $person -> person_code = Input::post('value');
                $saved = $person -> save();
                
                Controller_Client::cre_cln_history(Input::post('pk'), 'Labots klienta personas kods');
            }
            else if(Input::post('name') == 'client_phone')
            {
                $client = Model_User::find(Input::post('pk'));
                $person = Model_Person::find($client -> person_id);
                $person -> mobile_phone = Input::post('value');
                $saved = $person -> save();
                
                Controller_Client::cre_cln_history(Input::post('pk'), 'Labots klienta telefona numurs');
            }
            else if(Input::post('name') == 'client_email')
            {
                $client = Model_User::find(Input::post('pk'));
                $client -> email = Input::post('value');
                $saved = $client -> save();
            }
            else if(Input::post('name') == 'activate')
            {
                $client = Model_User::find(Input::post('pk'));
                $client -> is_active = Input::post('value');
                $saved = $client -> save();
                
                if($saved) 
                {
                    Controller_Client::cre_cln_history(Input::post('pk'), 'Atvērts lietotāja konts');
                    
                    $json_string = '{"user_id":' . Input::post('pk') .',"saved":"true"}';
                    return $json_string;
                }
                else return false;
                
            }
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
            $new_code -> used_in = 'Abonentu daļa';
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
            if(!empty($exists_srv))
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
    
}
