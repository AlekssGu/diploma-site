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
 * Galvenais kontrolieris
 *
 * Sistēmas galvenais kontrolieris
 * 
 */
class Controller_Client extends Controller_Template
{

        /**
         * Nodaļa: 3.3.3.1.	Klienta informācijas apskatīšana (klients)
         * Identifikators: CLN_SHOW_INFO
         *
         * Ļauj klientam apskatīt informāciju par sevi 
         * 
         */
	public function action_client()
	{
            if(Auth::check())
            {
                // dati skatam
                $data = array();
                
                // atrod nepieciešamos objektus un paņem no tiem nepieciešamos elementus
                $user = Model_User::find(Auth::get('id')); // klients
                $userdata = Model_Person::find($user->person_id); // klienta dati
                //
                // deklarētā adrese
                $useraddr1 = Model_Address::find($userdata -> address_id, array(
                                            'where' => array(
                                                array('addr_type', 'D')
                                            )
                                )); 
                
                // faktiskā adrese
                $useraddr2 = Model_Address::find($userdata -> secondary_addr_id, array(
                                            'where' => array(
                                                array('addr_type', 'F')
                                            )
                                )); 
                
                $city1 = Model_City::find($useraddr1->city_id); // deklarētās adreses pilsēta
                $city2 = Model_City::find($useraddr2->city_id); // faktiskās adreses pilsēta
                $cities = Model_City::find('all'); // visas pilsētas
                
                // klienta objekti (dzīvokļi, mājas u.c. ēkas, kur uzstādīti skaitītāji)
                $query = DB::select(array('objects.id', 'object_id'),
                                    'objects.name')
                            -> from('objects')
                            -> join('addresses')
                            -> on('addresses.id', '=', 'objects.address_id')
                            -> where('objects.client_id',Auth::get('id'))
                            -> and_where('addresses.addr_type','=','O')
                            -> and_where('objects.is_deleted','=','N');
                
                $objects = $query->as_object()->execute()->as_array();
                
                $query_history = DB::select('*')->from('client_histories')->where('client_id','=',Auth::get('id'))->limit(15)->order_by('id','desc');
                $history = $query_history->as_object()->execute()->as_array();
                
                //Sagatavo datus skatam
                $data['history'] = $history;
                $data['cities'] = $cities;
                $data['objects'] = $objects;
                $data['client_number'] = $user -> username;
                $data['fullname'] = $userdata -> name . ' ' . $userdata -> surname;
                $data['email'] = $user -> email;
                $data['phone'] = $userdata -> mobile_phone;
                
                // deklarētā adrese
                if($useraddr1 -> flat != 0) // Ja tā ir privātmājas adrese, tad dzīvoklis nepastāv
                {
                    $data['primary_address'] = $useraddr1 -> street . ' ' . $useraddr1 -> house . ' - ' . $useraddr1 -> flat . ', ' . $useraddr1 -> district . ', ' . $city1->city_name . ', ' . $useraddr1 -> post_code;
                }
                else $data['primary_address'] = $useraddr1 -> street . ' ' . $useraddr1 -> house . ', ' . $useraddr1 -> district . ', ' . $city1->city_name . ', ' . $useraddr1 -> post_code;
                
                // faktiskā adrese
                if($useraddr2 -> flat != 0) // Ja tā ir privātmājas adrese, tad dzīvoklis nepastāv
                {
                    $data['secondary_address'] = $useraddr2 -> street . ' ' . $useraddr2 -> house . ' - ' . $useraddr2 -> flat . ', ' . $useraddr2 -> district . ', ' . $city2->city_name . ', ' . $useraddr2 -> post_code;
                }
                else $data['secondary_address'] = $useraddr2 -> street . ' ' . $useraddr2 -> house . ', ' . $useraddr2 -> district . ', ' . $city2->city_name . ', ' . $useraddr2 -> post_code;
                
                
                
                $this -> template -> title = "Klienta informācija - Pilsētas ūdens";
                $this -> template -> content = View::forge('client/client',$data);
            }
            else //Nav autorizējies
            {
                Response::redirect('/');
            }
	}
        
        /**
	 * Funkcija: 3.3.3.2.	Lietotāja paroles izsūtīšana (klients)
         * Identifikators: CLN_EDIT_INFO
	 *
	 * Ļauj lietotājam mainīt e-pastu, telefona numuru un paroli
         * Dati tiek sūtīti ar jQuery AJAX palīdzību
         * 
	 */
        public function action_change_data()
        {
            // Lietotājam jābūt autorizētam
            if(!Auth::check())
            {
                Response::redirect('/');
            }
            
            //Padod datus no formā iebūvētās labošanas
            //Mainam e-pastu
            if(Input::method()=='POST' && Input::post('action') == 'email')
            {
                    try {
                            //Mēģinam mainīt e-pastu
                            $changed = Auth::update_user(
                                                array(
                                                        'email' => Input::post('value')
                                                     )
                                                );
                            //Ja mainīts, tad saglabā to pie klienta vēstures
                            if($changed) 
                            {
                                Controller_Client::cre_cln_history(Auth::get('id'),'Mainīta e-pasta adrese');
                                return true;
                            }
                            //Nav izdevies mainīt
                            else return false;

                    //Nav izdevies mainīt
                    } catch (\SimpleUserUpdateException $e)
                    {
                        $response = new Response();
                        $response -> set_status(304); //Not modified
                        return $response;
                    }
            }
            //Padod datus no formā iebūvētās labošanas
            //Mainam telefona numuru
            if(Input::method()=='POST' && Input::post('action') == 'phone')
                {
                            //Meklējam lietotāja un personas objektus
                            $user_object = Model_User::find(Auth::get('id'));
                            $person_object = Model_Person::find($user_object->person_id);
                            
                            //Ja ievadīts garāks telefona numurs, nekā Latvijā iespējams, tad kļūda
                            if(mb_strlen(Input::post('value'))> 13)
                            {
                                $response = new Response();
                                $response -> set_status(304);
                                return $response;
                            }
                            
                            //Mainam telefona numuru
                            $person_object -> mobile_phone = Input::post('value');
                            
                            //Ja mainīts, tad saglabā to pie klienta vēstures
                            if($person_object -> save()) 
                            {
                                Controller_Client::cre_cln_history(Auth::get('id'),'Mainīts telefona numurs');
                                return true;
                            }
                            //Uzstāda "Not modified" statusu
                            else 
                            {
                                $response = new Response();
                                $response -> set_status(304);
                                return $response;
                            }
                }
            
            //Ja ir POST dati, tad tas nozīmē, ka maina paroli
            if(Input::method()=='POST' && Security::check_token())
            {                
                //Parolēm jāsakrīt
                if(Input::post('new_password') != Input::post('new_secpassword'))
                {
                    Session::set_flash('error','Jaunājām parolēm jāsakrīt!');
                }
                //Varbūt paroli nemaina?
                elseif(Input::post('old_password') == Input::post('new_password'))
                {
                    Session::set_flash('error','Parole netika nomainīta! Vecā parole sakrīt ar jauno paroli.');
                }
                else
                {
                    //Mainam paroli
                    $changed = Auth::change_password(Input::post('old_password'),Input::post('new_password'));
                        if($changed) 
                        {
                            Session::set_flash('success','Parole mainīta!');
                            Controller_Client::cre_cln_history(Auth::get('id'),'Mainīta parole');
                        }
                        else Session::set_flash('error','Parole netika nomainīta! Ievadīta nepareiza vecā parole.');
                }
            }
            
            $this->template->title = "Paroles maiņa - Pilsētas ūdens";
            $this->template->content = View::forge('connection/change-data');
        }
        
        /**
	 * Funkcija: 3.3.3.3.	Klienta objektu saraksta apskatīšana (klients)
         * Identifikators: CLN_SHOW_ALL_OBJ
	 *
	 * Ļauj lietotājam apskatīt visus savus objektus saraksta veidā
         * 
	 */
        public function action_all_objects()
        {
            if(Auth::check())
            {
                $data = array();
                
                //Vaicājums savāc visu informāciju par klienta objektiem
                $query = DB::select(array('objects.id', 'object_id'),
                                    'objects.name',
                                    'objects.notes',
                                    'addresses.*',
                                    'cities.city_name') 
                            -> from('objects')
                            -> join('addresses')
                            -> on('addresses.id', '=', 'objects.address_id')
                            -> join('cities')
                            -> on('cities.id','=','addresses.city_id')
                            -> where('objects.client_id',Auth::get('id'))
                            -> and_where('addresses.addr_type','=','O')
                            -> and_where('objects.is_deleted','=','N');
                
                $result = $query -> as_object() -> execute() -> as_array();
                
                //Saveido adreses
                foreach ($result as $value => $key) {
                    //Ja ir norādīts dzīvoklis, tad rādīt dzīvokli (mājām nav dzīvokļa nr)
                    if($key->flat)
                    {
                        $key->address = $key -> street . ' ' . $key -> house . ' - ' . $key -> flat . ''
                            . ', ' . $key -> post_code . ', ' . $key -> district . ', ' . $key -> city_name;   
                    }
                    //Nerādīt dzīvokļa numuru
                    else 
                    {
                        $key->address = $key -> street . ' ' . $key -> house . ''
                            . ', ' . $key -> post_code . ', ' . $key -> district . ', ' . $key -> city_name; 
                    }
                }
                
                //Dati skatam
                $data['all_objects'] = $result;
                
                $this -> template -> title = "Visi objekti - Pilsētas ūdens";
                $this -> template -> content = View::forge('client/all_objects',$data);
            }
            else
            {
                Response::redirect('/');
            }
        }

        /**
	 * Funkcija: 3.3.3.4.	Klientam piesaistītā objekta apskatīšana (klients)
         * Identifikators: CLN_SHOW_OBJ
	 *
	 * Ļauj lietotājam apskatīt sava objekta informāciju
         * 
	 */
        public function action_all_objects_show($id = null)
        {
            //Ja ir autorizējies
            if(Auth::check())
            {
                //Ja ir padots objekta id, kuru apskatīt
                if(isset($id))
                {
                    $object = Model_Object::find($id);
                    
                    if($object && $object->client_id = Auth::get('id'))
                    {
                        $data = array();
                        
                        $query_objects = DB::select(array('objects.id', 'object_id'),
                                            'objects.name',
                                            'objects.notes',
                                            'addresses.*',
                                            'cities.city_name') 
                                    -> from('objects')
                                    -> join('addresses')
                                    -> on('addresses.id', '=', 'objects.address_id')
                                    -> join('cities')
                                    -> on('cities.id','=','addresses.city_id')
                                    -> where('objects.client_id',Auth::get('id'))
                                    -> and_where('objects.id','=',$id);

                        $result_objects = $query_objects -> as_object() -> execute() -> as_array();
                        
                        foreach ($result_objects as $value => $key) {
                            if($key->flat)
                            {
                                $key->address = $key -> street . ' ' . $key -> house . ' - ' . $key -> flat . ''
                                    . ', ' . $key -> district . ', ' . $key -> post_code . ', ' . $key -> city_name;   
                            }
                            else 
                            {
                                $key->address = $key -> street . ' ' . $key -> house . ''
                                    . ', ' . $key -> post_code . ', ' . $key -> district . ', ' . $key -> city_name; 
                            }
                        }
                
                        // Funkcija: MTR_SHOW_INFO
                        $query_meters = DB::select('*')
                                                -> from('last_readings')
                                                -> where('last_readings.client_id',Auth::get('id'))
                                                -> and_where('last_readings.object_id','=',$id)
                                                -> and_where('last_readings.is_active','=','Y')
                                                -> order_by('last_readings.meter_number','ASC');
                        
                        $result_meters = $query_meters -> as_object() -> execute() -> as_array();
                        
                        $query_srv = DB::select(array('user_services.id', 'usr_srv_id'),
                                                    'services.*',
                                                    'user_services.*') 
                                            -> from('user_services')
                                            -> join('objects')
                                            -> on('objects.id', '=', 'user_services.obj_id')
                                            -> join('services')
                                            -> on('services.id', '=', 'user_services.srv_id')
                                            -> where('objects.client_id',Auth::get('id'))
                                            -> and_where('objects.id','=',$id)
                                            -> and_where('user_services.is_active','=','Y');
                        
                        $result_srv = $query_srv -> as_object() -> execute() -> as_array();
                        
                        if(empty(array_filter($result_objects)))
                        {
                            Session::set_flash('error','Neveiksme! Nav iespējams apskatīt objektu.');
                            Response::redirect('/klients');
                        }
                        
                        $data['objects'] = $result_objects;
                        $data['services'] = $result_srv;
                        $data['meters'] = $result_meters;
                        
                        $this -> template -> title = "Apskatīt objektu - Pilsētas ūdens";
                        $this -> template -> content = View::forge('client/show_object',$data);
                    }
                    else
                    {
                        Session::set_flash('error','Neveiksme! Nav iespējams apskatīt objektu.');
                        Response::redirect('/klients');
                    }
                }
                else
                {
                    Session::set_flash('error','Neveiksme! Šāds objekts nepastāv!');
                    Response::redirect('/klients');
                }
            }
            else
            {
                Response::redirect('/');
            }
        }
        
        /**
	 * Funkcija: 3.3.2.2.	Skaitītāja rādījuma pievienošana (klients)
         * Identifikators: MTR_ADD_READING
	 *
	 * Klients var ievadīt skaitītāja rādījumus par kārtējo periodu
         * 
	 */
        public function action_add_reading()
        {
            //Ir autorizējies
            if(Auth::check())
            {
                //Ir padoti dati un CSRF žetons (token)
                if(Input::method() == 'POST' && Security::check_token()) 
                    {
                        //Ir padots rādījums un skaitītāja id
                        if((Input::post('reading') != '') && (Input::post('meter_id') != ''))
                        {
                            
                            if(!is_numeric(Input::post('reading')))
                            {
                                Session::set_flash('error','Skaitītaja rādījumam ir jābūt skaitliskai vērtībai!');
                                Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                            }
                            
                            //Ja nospiesta poga "Iesniegt"
                            if(Input::post('action') == 1)
                            {
                                //ir padots reading_id, kas nozīmē, ka tiek iesniegts no 
                                //rādījumu vēstures formas
                                if($existing_reading = Model_Reading::find(Input::post('reading_id')))
                                {
                                    //Pārbauda iepriekšējo rādījumu, lai jaunais rādījums ir lielāks
                                    $last_rdn_query = DB::select(DB::expr('MAX(id)'))
                                                ->from('readings')
                                                ->where('readings.id','!=',Input::post('reading_id'))
                                                ->and_where('readings.meter_id','=',Input::post('meter_id'));
                                    $last_rdn_id = $last_rdn_query -> as_object() -> execute() -> as_array();
                                    
                                    $last_rdn = Model_Reading::find($last_rdn_id[0]);
                                    
                                    if(Input::post('reading') > $last_rdn->lead)
                                    {
                                        $existing_reading -> lead = Input::post('reading');
                                        $existing_reading -> date_taken = Date::forge()->format('%Y-%m-%d');
                                        $existing_reading -> status = 'Iesniegts';
                                        $existing_reading -> notes = 'Tiek izskatīts klientu daļā';

                                        $saved = $existing_reading -> save();
                                    }
                                    //Jaunais rādījums ir mazāks par iepriekšējo
                                    else
                                    {
                                        Session::set_flash('error','Skaitītaja rādījumam ir jābūt lielākam par iepriekšējo!');
                                        Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                    }
                                }
                                //Rādījums tiek iesniegts no visu skaitītāju saraksta
                                else 
                                {
                                    //Pārbauda iepriekšējo rādījumu, lai jaunais rādījums ir lielāks
                                    $last_rdn_query = DB::select(DB::expr('MAX(id)'))
                                                ->from('readings')
                                                ->where('readings.meter_id','=',Input::post('meter_id'));
                                    $last_rdn_id = $last_rdn_query -> as_object() -> execute() -> as_array();
                                    
                                    $last_rdn = Model_Reading::find($last_rdn_id[0]);

                                    if($last_rdn->status == 'Labošanā') 
                                    {
                                        Session::set_flash('error','Skaitītaja rādījums nav iesniegts, jo jums ir neiesniegti rādījumi!');
                                        Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                    }
                                    elseif(!in_array($last_rdn->status, array('Apstiprināts', 'Sākotnējais')))
                                    {
                                        Session::set_flash('error','Skaitītaja rādījums nav iesniegts, jo iepriekšējais rādījums vēl nav akceptēts!');
                                        Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                    }
                                    
                                    //Ir lielāks
                                    if(Input::post('reading') > $last_rdn->lead)
                                    {
                                        $new_reading = new Model_Reading();
                                        $new_reading -> meter_id = Input::post('meter_id');
                                        $new_reading -> lead = Input::post('reading');
                                        $new_reading -> date_taken = Date::forge()->format('%Y-%m-%d');
                                        $new_reading -> period = 'Mēnesis';
                                        $new_reading -> status = 'Iesniegts';
                                        $new_reading -> notes = 'Tiek izskatīts klientu daļā';

                                        $saved = $new_reading -> save();
                                    }
                                    //Nav lielāks
                                    else
                                    {
                                        Session::set_flash('error','Skaitītaja rādījumam ir jābūt lielākam par iepriekšējo!');
                                        Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                    }
                                }

                                if($saved && Input::post('reading_id') != '') 
                                {
                                    //Saglabā vēsturi
                                    Controller_Client::cre_cln_history(Auth::get('id'), 'Iesniegts skaitītāja rādījums');
                                    
                                    Session::set_flash('success','Skaitītaja rādījums iesniegts!');
                                    Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                }
                                else if($saved && Input::post('reading_id') == '')
                                {
                                    //Saglabā vēsturi
                                    Controller_Client::cre_cln_history(Auth::get('id'), 'Iesniegts skaitītāja rādījums');
                                    
                                    Session::set_flash('success','Skaitītaja rādījums iesniegts!');
                                    Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                }
                                else 
                                {
                                    Session::set_flash('error','Skaitītaja rādījums nav iesniegts!');
                                    Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                }
                            }
                            //Nospiesta poga "Pievienot"
                            else if(Input::post('action') == 2)
                            {
                                //ir padots reading_id, kas nozīmē, ka tiek iesniegts no 
                                //rādījumu vēstures formas
                                if($existing_reading = Model_Reading::find(Input::post('reading_id')))
                                {
                                    $existing_reading -> lead = Input::post('reading');
                                    $existing_reading -> date_taken = Input::post('date_taken');
                                    $existing_reading -> status = 'Labošanā';
                                    $existing_reading -> notes = 'Rādījums tiek labots';
                                    
                                    $saved = $existing_reading -> save();
                                    
                                }
                                //Pievienots jauns rādījums
                                else 
                                {
                                    //Ir pievienots un uzreiz iesniegts jauns rādījums
                                    //tāpēc pārbauda, vai ir kāds neiesniegts
                                    //Pārbauda iepriekšējo rādījumu, lai jaunais rādījums ir lielāks
                                    $last_rdn_query = DB::select(DB::expr('MAX(id)'))
                                                ->from('readings')
                                                ->where('readings.meter_id','=',Input::post('meter_id'));
                                    $last_rdn_id = $last_rdn_query -> as_object() -> execute() -> as_array();
                                    
                                    $last_rdn = Model_Reading::find($last_rdn_id[0]);

                                    if($last_rdn->status == 'Labošanā') 
                                    {
                                        Session::set_flash('error','Skaitītaja rādījums nav pievienots, jo ir neiesniegti rādījumi!');
                                        Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                    }
                                    elseif(!in_array($last_rdn->status, array('Apstiprināts', 'Sākotnējais')))
                                    {
                                        Session::set_flash('error','Skaitītaja rādījums nav pievienots, jo iepriekšējais rādījums vēl nav akceptēts!');
                                        Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                    }


                                    $new_reading = new Model_Reading();
                                    $new_reading -> meter_id = Input::post('meter_id');
                                    $new_reading -> lead = Input::post('reading');
                                    $new_reading -> date_taken = Date::forge()->format('%Y-%m-%d');
                                    $new_reading -> period = 'Mēnesis';
                                    $new_reading -> status = 'Labošanā';
                                    $new_reading -> notes = 'Rādījums tiek labots';

                                    $saved = $new_reading -> save();
                                    
                                }
                                
                                if($saved && Input::post('reading_id') != '') 
                                {
                                    //Saglabā vēsturi
                                    Controller_Client::cre_cln_history(Auth::get('id'), 'Labots skaitītāja rādījums');
                                    
                                    Session::set_flash('success','Skaitītaja rādījums labots!');
                                    Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                }
                                else if($saved && Input::post('reading_id') == '')
                                {
                                    //Saglabā vēsturi
                                    Controller_Client::cre_cln_history(Auth::get('id'), 'Pievienots skaitītāja rādījums');
                                    
                                    Session::set_flash('success','Skaitītaja rādījums pievienots!');
                                    Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                }
                                else
                                {
                                    Session::set_flash('error','Skaitītaja rādījums nav pievienots!');
                                    Response::redirect('/klients/objekti/radijumi/' . Input::post('meter_id'));
                                }
                            }
                        }
                        else
                        {
                                Session::set_flash('error','Skaitītaja rādījums nav iesniegts, jo netika iesniegti nekādi dati!');
                                Response::redirect_back();   
                        }
                    }
                    else
                    {
                        Session::set_flash('error','Skaitītaja rādījums nav iesniegts, jo netika iesniegti nekādi dati!');
                        Response::redirect_back();   
                    }
                    
                $this -> template -> title = "Skaitītāju rādījumi - Pilsētas ūdens";
                //$this -> template -> content = View::forge('client/show_object/' . Input::post('meter_id'));
            }
            else
            {
                Response::redirect('/');
            }
        }
        
        /**
	 * Funkcija: 3.3.2.5.	Skaitītāja rādījumu vēstures apskate (klients)
         * Identifikators: MTR_HISTORY
	 *
	 * Klients var apskatīt savu skaitītāja rādījumu vēsturi (gan vēl neiesniegto, gan iesniegto rādījumu informāciju).
         * TODO: pārbaudīt vai klientam ir tāds skaitītājs, lai jebkurš nevarētu apskatīt cita klienta datus
	 */
        public function action_readings_history($id = null)
        {
            //Vai ir autorizējies
            if(Auth::check())
            {   
                //Vai ir padoti dati ar GET
                if(Input::method()) 
                {
                    if($id != '')
                    {
                        $data = array();

                        $query_meter = DB::select('meters.*')
                                        ->from('meters')
                                        ->join('user_services')->on('user_services.id','=','meters.service_id')
                                        ->join('objects')->on('objects.id','=','user_services.obj_id')
                                        ->where('meters.id','=',$id)
                                        ->and_where('objects.client_id','=',Auth::get('id'));
                        $mtr_data = $query_meter -> as_object() -> execute() -> as_array();

                        if(count($mtr_data) == 0)
                        {
                            Session::set_flash('error','Kļūda! Šāds skaitītājs neeksistē!');
                            Response::redirect('/klients');
                        }
                        
                        $query_object = DB::select('*')->from('user_services')->where('user_services.id','=',$mtr_data[0]->service_id);
                        $object_data = $query_object -> as_object() -> execute() -> as_array();

                        $query_readings = DB::select('*')
                                        -> from('readings')
                                        -> where('readings.meter_id','=',$id)
                                        -> order_by('readings.id', 'desc');

                        $data['readings'] = $query_readings -> as_object() -> execute() -> as_array();
                        $data['object_id'] = $object_data[0] -> obj_id;
                        $data['meter_number'] = $mtr_data[0] -> meter_number;
                        $data['meter_id'] = $mtr_data[0] -> id;
                    }
                }
                else
                {
                    Session::set_flash('error','Sistēmas kļūda! Netika padoti dati!');
                    Response::redirect_back();
                }
                  
                    
                $this -> template -> title = "Skaitītāju rādījumi - Pilsētas ūdens";
                $this -> template -> content = View::forge('client/show_readings',$data);
            }
            else
            {
                Response::redirect('/');
            }
        }
        
        /**
	 * Funkcija: 3.3.2.6.	Klienta vēstures veidošana (sistēma)
         * Identifikators: GLOBAL_CLN_CRE_HISTORY
	 *
         * Veido klienta vēsturi
         * Parametri:
         * p_cln_id = Klienta identifikators
         * p_notes = Vēstures teksts/komentārs
         * 
	 */
        static function cre_cln_history(
            $p_cln_id = null,
            $p_notes = null)
        {
            if($p_cln_id != '' && $p_notes != '')
            {
                $new = new Model_Client_History();
                $new -> client_id = $p_cln_id;
                $new -> notes = $p_notes;
                
                if($new->save()) return true;
                else return false;
            }
        }
        
        /**
	 * Funkcija: 3.3.4.8.	Objektam piesaistītā pakalpojuma apskatīšana (klients, darbinieks)
         * Identifikators: SRV_SHOW_SERVICE
	 *
         * Klients var apskatīties viena pakalpojuma informāciju
         * 
	 */
        public function action_show_service()
        {
            $object_id = $this -> param('object_id');
            $service_id = $this -> param('service_id');
            
            if(Auth::check())
            {
                if(Input::method()) 
                    {
                        //Ja ir padoti parametri
                        if($object_id != '' && $service_id != '')
                        {
                            $data = array();

                            if(Auth::member(1))
                            {
                                //Atlasam pakalpojumus pēc padotajiem parametriem
                                $query_services = DB::select('*')
                                                -> from('all_obj_services')
                                                -> where('all_obj_services.object_id','=',$object_id)
                                                -> and_where('all_obj_services.usr_srv_id','=',$service_id)
                                                -> and_where('all_obj_services.is_active','=','Y');

                                //Atlasam objektus
                                $query_object = DB::select('client_objects.object_name',
                                                           'client_objects.object_addr',
                                                           'client_objects.object_id')
                                                -> from('client_objects')
                                                -> where('client_objects.object_id','=',$object_id)
                                                -> and_where('client_objects.client_id','=',Auth::get('id'))
                                                -> and_where('client_objects.is_deleted','=','N');;

                                //Atlasam skaitītājus
                                $query_meters = DB::select('*')
                                                -> from('meters')
                                                -> join('user_services')->on('user_services.id','=','meters.service_id')
                                                -> where('meters.service_id','=',$service_id)
                                                -> and_where('user_services.is_active','=','Y');

                                $data['service'] = $query_services -> as_object() -> execute() -> as_array();
                                $data['object'] = $query_object -> as_object() -> execute() -> as_array();
                                $data['meters'] = $query_meters -> as_object() -> execute() -> as_array();

                                //Ja kāds grib apskatīties cita klienta datus, tad būs skaits 0
                                if(count(array_filter($data['object']))==0)
                                {
                                    Session::set_flash('error','Nav iespējams apskatīties šī objekta datus!');
                                    Response::redirect('/klients');   
                                }
                                elseif(count(array_filter($data['service']))==0)
                                {
                                    Session::set_flash('error','Nav iespējams apskatīties šī objekta datus!');
                                    Response::redirect('/klients');   
                                }
                                
                                $this -> template -> content = View::forge('client/show_services',$data);
                                
                            }
                            else if(Auth::member(50))
                            {
                                $user = Model_Object::find($object_id);
                                
                                if($user) $user_id = $user->client_id;
                                else
                                {
                                    Session::set_flash('error','Nav iespējams apskatīties šī objekta datus!');
                                    Response::redirect('/');
                                }
                                
                                //Atlasam pakalpojumus pēc padotajiem parametriem
                                $query_services = DB::select('*')
                                                -> from('all_obj_services')
                                                -> where('all_obj_services.object_id','=',$object_id)
                                                -> and_where('all_obj_services.usr_srv_id','=',$service_id)
                                                -> and_where('all_obj_services.is_active','=','Y');

                                //Atlasam objektus
                                $query_object = DB::select('client_objects.object_name',
                                                           'client_objects.object_addr',
                                                           'client_objects.object_id')
                                                -> from('client_objects')
                                                -> where('client_objects.object_id','=',$object_id)
                                                -> and_where('client_objects.is_deleted','=','N');

                                //Atlasam skaitītājus
                                $query_meters = DB::select(array('meters.id','mtr_id')
                                                           ,'user_services.*'
                                                           ,'meters.*')
                                                -> from('meters')
                                                -> join('user_services')->on('user_services.id','=','meters.service_id')
                                                -> where('meters.service_id','=',$service_id)
                                                -> and_where('user_services.is_active','=','Y');

                                $data['service'] = $query_services -> as_object() -> execute() -> as_array();
                                $data['object'] = $query_object -> as_object() -> execute() -> as_array();
                                $data['meters'] = $query_meters -> as_object() -> execute() -> as_array();

                                //Ja kāds grib apskatīties cita klienta datus, tad būs skaits 0
                                if((count($query_object)==0) || (count($query_services)==0))
                                {
                                    Session::set_flash('error','Nav iespējams apskatīties šī objekta datus!');
                                    Response::redirect('/');   
                                }
                                
                                $this -> template -> content = View::forge('worker/client_services',$data);
                                
                            }
                            else Response::redirect('/'); //Nav pareiza lietotāja grupa, sūtam prom
                            
                        }
                    }
                $this -> template -> title = "Objekta pakalpojumi - Pilsētas ūdens";
            }
            else
            {
                Response::redirect('/');
            }
        }
        
        /**
	 * Funkcija: 3.3.4.5.	Pakalpojuma pieteikšana (klients)
         * Identifikators: SRV_ADD_SERVICE
	 *
         * Klients var pieteikt jaunu pakalpojumu saviem objektiem.
         * 
	 */
        public function action_request_service()
        {
            $data = array();
            
            //Funkcija datuma pārbaudei
            function validateDate($date)
            {
                $d = DateTime::createFromFormat('d.m.Y', $date);
                return $d && $d->format('d.m.Y') == $date;
            }
            
            
            if(Auth::check())
            {
                //Visi pakalpojumi
                $query_services = DB::select('*')->from('services');
                $services = $query_services -> as_object() -> execute() -> as_array();
                
                //Visi klienta objekti
                $query_objects = DB::select('*')
                        ->from('client_objects')
                        ->where('client_id','=',Auth::get('id'))
                        ->and_where('is_deleted','=','N');
                $objects = $query_objects -> as_object() -> execute() -> as_array();
                
                $data['services'] = $services;
                $data['objects'] = $objects;
                
                if(Input::method() == 'POST' && Security::check_token())
                {
                    //Pārbauda, vai objektam jau nav tāds pakalpojums
                    $check_services = DB::select('*')->
                                from('user_services')->
                                where('obj_id','=',Input::post('object'))->
                                and_where('srv_id','=',Input::post('service'))->
                                and_where('is_active','=','Y')->
                                limit(1)->
                                execute();
                    $exists_service = DB::count_last_query();
                    
                    $check_requests = DB::select('*')
                                    ->from('usr_service_requests')
                                    ->where('client_id','=',Auth::get('id'))
                                    ->and_where('object_id','=',Input::post('object'))
                                    ->and_where('service_id','=',Input::post('service'))
                                    ->limit(1)
                                    ->execute();
                    $exists_request = DB::count_last_query();
                    
                    //Objektam ir piesaistīts tāds pakalpojums
                    if($exists_service)
                    {
                        Session::set_flash('error','Izvēlētajam objektam jau ir piesaistīts izvēlētais pakalpojums!');
                        Response::redirect('/klients/pakalpojumi/pasutit');
                    }
                    
                    //Objektam ir pasūtīts tāds pakalpojums
                    if($exists_request)
                    {
                        Session::set_flash('error','Izvēlētajam objektam jau ir pasūtīts izvēlētais pakalpojums!');
                        Response::redirect('/klients/pakalpojumi/pasutit');
                    }
                    
                    if(     Input::post('object') == ''
                            || Input::post('service') == ''
                            || Input::post('date_from') == ''
                            || Input::post('date_to') == ''
                      )
                    {
                        Session::set_flash('error','Kļūda! Aizpildiet visus laukus (piezīmes nav obligātas)!');
                        Response::redirect('/klients/pakalpojumi/pasutit');
                    }
                    
                    if(!validateDate(Input::post('date_from')) || !validateDate(Input::post('date_to')))
                    {
                        Session::set_flash('error','Kļūda! Lūdzu ievadiet korektu datumu!');
                        Response::redirect('/klients/pakalpojumi/pasutit');
                    }
                        
                    
                    $new_request = new Model_Usr_Service_Request();
                    $new_request -> client_id = Auth::get('id');
                    $new_request -> object_id = Input::post('object');
                    $new_request -> service_id = Input::post('service');
                    $new_request -> date_from = date_format(date_create(Input::post('date_from')), 'Y-m-d');
                    $new_request -> date_to = date_format(date_create(Input::post('date_to')), 'Y-m-d');
                    $new_request -> notes = Input::post('notes');
                    $new_request -> status = 'Pieteikts';
                    
                    if($new_request -> save())
                    {
                        Controller_Client::cre_cln_history(Auth::get('id'),'Pasūtīts pakalpojums');
                        Session::set_flash('success','Izvēlētajam objektam pasūtīts izvēlētais pakalpojums!');
                        Response::redirect('/klients/pakalpojumi/pasutit');
                    }
                    else
                    {
                        Session::set_flash('error','Notikusi kļūda! Pakalpojums objektam netika pasūtīts.');
                        Response::redirect('/klients/pakalpojumi/pasutit');
                    }
                }
                else
                {
                    $this -> template -> content = View::forge('client/request_service',$data);
                }
                
            }
            else Response::redirect('/');
            
            $this -> template -> title = "Pasūtīt pakalpojumu - Pilsētas ūdens";
        }
        
        /**
	 * Funkcija: 3.3.4.8.	Atteikšanās no objektam piesaistītā pakalpojuma (klients)
         * Identifikators: SRV_REMOVE_SERVICE
	 *
         * Klients var atteikties no piesaistītā pakalpojuma
         * 
	 */
        public function action_dismiss_service()
        {
            if(Auth::check())
            {
                $check_exists = DB::select('*')
                                ->from('usr_service_requests')
                                ->where('client_id','=',Auth::get('id'))
                                ->and_where('object_id','=',Input::post('object'))
                                ->and_where('usr_srv_id','=',Input::post('service'))
                                ->and_where('status','!=','Atteikts')
                                ->execute();
                $exists = DB::count_last_query();
                
                if($exists > 0)
                {
                    Session::set_flash('error','Šāds pakalpojuma pieprasījums jau datubāzē pastāv!');
                    Response::redirect_back();
                }
                
                //Veido jaunu pieprasījumu
                $new_request = new Model_Usr_Service_Request();
                $new_request -> client_id = Auth::get('id');
                $new_request -> object_id = Input::post('object');
                $new_request -> usr_srv_id = Input::post('service');
                $new_request -> date_from = date_format(date_create(Input::post('date_from')), 'Y-m-d');
                $new_request -> notes = Input::post('notes');
                $new_request -> status = 'Pieteikts';
                //Ja izdevies saglabāt jauno pieprasījumu
                if($new_request->save())
                {
                    Session::set_flash('success','Pakalpojuma atteikšanās pieprasījums izveidots!');
                    Response::redirect_back();
                }
                else
                {
                    Session::set_flash('error','Pakalpojuma atteikšanās pieprasījumu nebija iespējams izveidot!');
                    Response::redirect_back();
                }
            }
            else
            {
                Response::redirect('/');
            }
        }
        
        /**
	 * Funkcija:
         * Identifikators: 
	 *
	 * //
         * 
	 */
        public function action_all_readings()
        {
            //Ja ir autorizējies
            if(Auth::check())
            {
                        $data = array();
                        
                        $query_objects = DB::select(array('objects.id', 'object_id'),
                                            'objects.name',
                                            'objects.notes',
                                            'addresses.*',
                                            'cities.city_name') 
                                    -> from('objects')
                                    -> join('addresses')
                                    -> on('addresses.id', '=', 'objects.address_id')
                                    -> join('cities')
                                    -> on('cities.id','=','addresses.city_id')
                                    -> where('objects.client_id',Auth::get('id'));

                        $result_objects = $query_objects -> as_object() -> execute() -> as_array();
                        
                        foreach ($result_objects as $value => $key) {
                            if($key->flat)
                            {
                                $key->address = $key -> street . ' ' . $key -> house . ' - ' . $key -> flat . ''
                                    . ', ' . $key -> post_code . ', ' . $key -> district . ', ' . $key -> city_name;   
                            }
                            else 
                            {
                                $key->address = $key -> street . ' ' . $key -> house . ''
                                    . ', ' . $key -> post_code . ', ' . $key -> district . ', ' . $key -> city_name; 
                            }
                        }
                
                        // Funkcija: MTR_SHOW_INFO
                        $query_meters = DB::select('*')
                                                -> from('last_readings')
                                                -> where('last_readings.client_id',Auth::get('id'))
                                                -> and_where('last_readings.is_active','=','Y')
                                                -> order_by('last_readings.meter_number','ASC');
                        
                        $result_meters = $query_meters -> as_object() -> execute() -> as_array();
                        
                        $query_srv = DB::select(array('user_services.id', 'usr_srv_id'),
                                                    'services.*',
                                                    'user_services.*') 
                                            -> from('user_services')
                                            -> join('objects')
                                            -> on('objects.id', '=', 'user_services.obj_id')
                                            -> join('services')
                                            -> on('services.id', '=', 'user_services.srv_id')
                                            -> where('objects.client_id',Auth::get('id'));
                        
                        $result_srv = $query_srv -> as_object() -> execute() -> as_array();
                        
                        $data['objects'] = $result_objects;
                        $data['services'] = $result_srv;
                        $data['meters'] = $result_meters;
                        
                        $this -> template -> title = "Apskatīt objektu - Pilsētas ūdens";
                        $this -> template -> content = View::forge('client/readings',$data);
            }
            else
            {
                Response::redirect('/');
            }
        }
}
