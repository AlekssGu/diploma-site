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
	 * Galvenā lapas (index) funkcija
	 *
	 * Uzstāda: lapas nosaukums, saturs
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
                            -> and_where('addresses.addr_type','=','O');
                
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
         *   Darbinieks var pievienot objektu
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
                            //Ja objekts izveidots, tad saglabā to abonenta vēsturē un paziņo par to lietotājam
                            Controller_Client::cre_cln_history(Input::post('client_id'),'Piesaistīts objekts');
                            
                            Session::set_flash('success','Objekts pievienots!');
                            Response::redirect('/darbinieks/abonenti');
                        }
                        else
                        {
                            //Ja netika izveidots objekts, tad dzēš iepriekš izveidoto adresi
                            $rollback = Model_Address::find($new_address->id);
                            $rollback->delete();
                            
                            Session::set_flash('error','Neveiksme! Nebija iespējams izveidot jaunu objektu.');
                            Response::redirect('/darbinieks/abonenti');
                        }
                    }
                    else
                    {
                        Session::set_flash('error','Neveiksme! Nebija iespējams izveidot jaunu adresi.');
                        Response::redirect('/darbinieks/abonenti');
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
         *  Klients var apskatīt visus objektus
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
        
        public function action_all_objects_delete($id = null)
        {
            if(Auth::check())
            {
                if(isset($id))
                {
                    $object = Model_Object::find($id);
                    
                    if($object && $object->client_id = Auth::get('id'))
                    {
                        $object->delete();
                        Response::redirect_back();
                    }
                    else
                    {
                        Session::set_flash('error','Neveiksme! Nebija iespējams dzēst objektu.');
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
        
        public function action_all_objects_show($id = null)
        {
            if(Auth::check())
            {
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
                                    . ', ' . $key -> post_code . ', ' . $key -> district . ', ' . $key -> city_name;   
                            }
                            else 
                            {
                                $key->address = $key -> street . ' ' . $key -> house . ''
                                    . ', ' . $key -> post_code . ', ' . $key -> district . ', ' . $key -> city_name; 
                            }
                        }
                
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
                                            -> and_where('objects.id','=',$id);
                        
                        $result_srv = $query_srv -> as_object() -> execute() -> as_array();
                        
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
        
        public function action_add_meter()
        {
            if(Auth::check() && Auth::member(50))
            {
                if(Input::method()=='POST' && Security::check_token())
                {
                    
                    $new_meter = new Model_Meter();
                    $new_meter -> service_id = Input::post('service_id');
                    $new_meter -> date_from = Date::forge(strtotime(Input::post('date_from')))->format('%Y-%m-%d');
                    $new_meter -> date_to = Date::forge(strtotime(Input::post('date_to')))->format('%Y-%m-%d');
                    $new_meter -> meter_type = Input::post('meter_type');
                    $new_meter -> water_type = Input::post('water_type');
                    $new_meter -> worker_id = 1;
                    $new_meter -> meter_number = Input::post('number');
                    $new_meter -> meter_model = 'dummy';
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
                            Response::redirect('/darbinieks/abonenti/apskatit-pakalpojumu/'.Input::post('object_id').'/'.Input::post('service_id'));   
                        }
                        else
                        {
                            $delete = Model_Meter::find($new_meter->id);
                            $delete -> delete();
                            
                            Session::set_flash('error','Neizdevās pievienot skaitītāju!');
                            Response::redirect('/darbinieks/abonenti/apskatit-pakalpojumu/'.Input::post('object_id').'/'.Input::post('service_id'));
                        }
                    }
                    else
                    {
                        Session::set_flash('error','Neveiksme! Neizdevās pievienot skaitītāju!');
                        Response::redirect('/darbinieks/abonenti/apskatit-pakalpojumu/'.Input::post('object_id').'/'.Input::post('service_id'));
                    }
                }
                else
                {
                    Response::redirect('/darbinieks/abonenti/apskatit-pakalpojumu/'.Input::post('object_id').'/'.Input::post('service_id'));
                }
            }
            else
            {
                Response::redirect('/');
            }
        }
        
        public function action_add_reading()
        {
            if(Auth::check())
            {
                if(Input::method() == 'POST' && Security::check_token()) 
                    {
                        if((Input::post('reading') != '') && (Input::post('meter_id') != ''))
                        {
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
                                        $existing_reading -> notes = 'Tiek izskatīts abonentu daļā';

                                        $saved = $existing_reading -> save();
                                    }
                                    else
                                    {
                                        Session::set_flash('error','Skaitītaja rādījumam ir jābūt lielākam par iepriekšējo!');
                                        Response::redirect('/abonents/objekti/radijumi/' . Input::post('meter_id'));
                                    }
                                }
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
                                        Response::redirect('/abonents/objekti/radijumi/' . Input::post('meter_id'));
                                    }
                                    
                                    if(Input::post('reading') > $last_rdn->lead)
                                    {
                                        $new_reading = new Model_Reading();
                                        $new_reading -> meter_id = Input::post('meter_id');
                                        $new_reading -> lead = Input::post('reading');
                                        $new_reading -> date_taken = Date::forge()->format('%Y-%m-%d');
                                        $new_reading -> period = 'Mēnesis';
                                        $new_reading -> status = 'Iesniegts';
                                        $new_reading -> notes = 'Tiek izskatīts abonentu daļā';

                                        $saved = $new_reading -> save();
                                    }
                                    else
                                    {
                                        Session::set_flash('error','Skaitītaja rādījumam ir jābūt lielākam par iepriekšējo!');
                                        Response::redirect('/abonents/objekti/radijumi/' . Input::post('meter_id'));
                                    }
                                }

                                if($saved && Input::post('reading_id') != '') 
                                {
                                    //Saglabā vēsturi
                                    Controller_Client::cre_cln_history(Auth::get('id'), 'Iesniegts skaitītāja rādījums');
                                    
                                    Session::set_flash('success','Skaitītaja rādījums iesniegts!');
                                    Response::redirect('/abonents/objekti/radijumi/' . Input::post('meter_id'));
                                }
                                else if($saved && Input::post('reading_id') == '')
                                {
                                    //Saglabā vēsturi
                                    Controller_Client::cre_cln_history(Auth::get('id'), 'Iesniegts skaitītāja rādījums');
                                    
                                    Session::set_flash('success','Skaitītaja rādījums iesniegts!');
                                    Response::redirect('/abonents/objekti/radijumi/' . Input::post('meter_id'));
                                }
                                else 
                                {
                                    Session::set_flash('error','Skaitītaja rādījums nav iesniegts!');
                                    Response::redirect('/abonents/objekti/radijumi/' . Input::post('meter_id'));
                                }
                            }
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
                                else 
                                {
                                    //Ir pievienots un uzreiz iesniegts jauns rādījums
                                    //tāpēc pārbauda, vai ir kāds neiesniegts
                                    $last_rdn = Model_Reading::find('last');

                                    if($last_rdn->status == 'Labošanā') 
                                    {
                                        Session::set_flash('error','Skaitītaja rādījums nav iesniegts, jo jums ir neiesniegti rādījumi!');
                                        Response::redirect('/abonents/objekti/radijumi/' . Input::post('meter_id'));
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
                                    Response::redirect('/abonents/objekti/radijumi/' . Input::post('meter_id'));
                                }
                                else if($saved && Input::post('reading_id') == '')
                                {
                                    //Saglabā vēsturi
                                    Controller_Client::cre_cln_history(Auth::get('id'), 'Pievienots skaitītāja rādījums');
                                    
                                    Session::set_flash('success','Skaitītaja rādījums pievienots!');
                                    Response::redirect('/abonents/objekti/radijumi/' . Input::post('meter_id'));
                                }
                                else
                                {
                                    Session::set_flash('error','Skaitītaja rādījums nav pievienots!');
                                    Response::redirect('/abonents/objekti/radijumi/' . Input::post('meter_id'));
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
        
        public function action_readings_history($id = null)
        {
            if(Auth::check())
            {
                if(Input::method()) 
                    {
                        if($id != '')
                        {
                            $data = array();
                            
                            $query_meter = DB::select('*')->from('meters')->where('meters.id','=',$id);
                            $mtr_data = $query_meter -> as_object() -> execute() -> as_array();
                            
                            $query_object = DB::select('*')->from('user_services')->where('user_services.id','=',$mtr_data[0]->service_id);
                            $object_data = $query_object -> as_object() -> execute() -> as_array();
                            
                            $query_readings = DB::select('*')
                                            -> from('readings')
                                            -> where('readings.meter_id','=',$id)
                                            -> order_by('readings.id', 'desc');

                            $data['readings'] = $query_readings -> as_object() -> execute() -> as_array();
                            $data['object_id'] = $object_data[0]-> obj_id;
                            $data['meter_number'] = $mtr_data[0]->meter_number;
                            $data['meter_id'] = $mtr_data[0]->id;
                        }
                    }
                    
                
                $this -> template -> title = "Skaitītāju rādījumi - Pilsētas ūdens";
                $this -> template -> content = View::forge('client/show_readings',$data);
            }
            else
            {
                Response::redirect('/');
            }
        }
        
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
                                                -> and_where('all_obj_services.usr_srv_id','=',$service_id);

                                //Atlasam objektus
                                $query_object = DB::select('client_objects.object_name',
                                                           'client_objects.object_addr',
                                                           'client_objects.object_id')
                                                -> from('client_objects')
                                                -> where('client_objects.object_id','=',$object_id)
                                                -> and_where('client_objects.client_id','=',Auth::get('id'));

                                //Atlasam skaitītājus
                                $query_meters = DB::select('*')
                                                -> from('meters')
                                                -> where('meters.service_id','=',$service_id);

                                $data['service'] = $query_services -> as_object() -> execute() -> as_array();
                                $data['object'] = $query_object -> as_object() -> execute() -> as_array();
                                $data['meters'] = $query_meters -> as_object() -> execute() -> as_array();

                                //Ja kāds grib apskatīties cita klienta datus, tad būs skaits 0
                                if(count($data['object'])==0)
                                {
                                    Session::set_flash('error','Nav iespējams apskatīties šī objekta datus!');
                                    Response::redirect('/klients');   
                                }
                                
                                $this -> template -> content = View::forge('client/show_services',$data);
                                
                            }
                            else if(Auth::member(50))
                            {
                                $user_id = Model_Object::find($object_id)->client_id;
                                
                                //Atlasam pakalpojumus pēc padotajiem parametriem
                                $query_services = DB::select('*')
                                                -> from('all_obj_services')
                                                -> where('all_obj_services.object_id','=',$object_id)
                                                -> and_where('all_obj_services.usr_srv_id','=',$service_id)
                                                -> and_where('all_obj_services.client_id','=',$user_id);

                                //Atlasam objektus
                                $query_object = DB::select('client_objects.object_name',
                                                           'client_objects.object_addr',
                                                           'client_objects.object_id')
                                                -> from('client_objects')
                                                -> where('client_objects.object_id','=',$object_id);

                                //Atlasam skaitītājus
                                $query_meters = DB::select('*')
                                                -> from('meters')
                                                -> where('meters.service_id','=',$service_id);

                                $data['service'] = $query_services -> as_object() -> execute() -> as_array();
                                $data['object'] = $query_object -> as_object() -> execute() -> as_array();
                                $data['meters'] = $query_meters -> as_object() -> execute() -> as_array();

                                //Ja kāds grib apskatīties cita klienta datus, tad būs skaits 0
                                if(count($data['object'])==0)
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
        
        public function action_request_service()
        {
            $data = array();
            
            if(Auth::check())
            {
                //Visi pakalpojumi
                $query_services = DB::select('*')->from('services');
                $services = $query_services -> as_object() -> execute() -> as_array();
                
                //Visi klienta objekti
                $query_objects = DB::select('*')->from('client_objects')->where('client_id','=',Auth::get('id'));
                $objects = $query_objects -> as_object() -> execute() -> as_array();
                
                $data['services'] = $services;
                $data['objects'] = $objects;
                
                if(Input::method() == 'POST' && Security::check_token())
                {
                    //Pārbauda, vai objektam jau nav tāds pakalpojums
                    $query = DB::select('*')->
                                from('user_services')->
                                where('obj_id','=',Input::post('object'))->
                                and_where('srv_id','=',Input::post('service'))->
                                limit(1)->
                                execute();
                    $exists = DB::count_last_query();
                    
                    //Objektam ir piesaistīts tāds pakalpojums
                    if($exists)
                    {
                        Session::set_flash('error','Izvēlētajam objektam jau ir piesaistīts izvēlētais pakalpojums!');
                        Response::redirect('/abonents/pakalpojumi/pasutit');
                    }
                    
                    $new_request = new Model_Usr_Service_Request();
                    $new_request -> client_id = Auth::get('id');
                    $new_request -> object_id = Input::post('object');
                    $new_request -> service_id = Input::post('service');
                    $new_request -> date_from = Input::post('date_from');
                    $new_request -> date_to = Input::post('date_to');
                    $new_request -> notes = Input::post('notes');
                    
                    if($new_request -> save())
                    {
                        Controller_Client::cre_cln_history(Auth::get('id'),'Pasūtīts pakalpojums');
                        Session::set_flash('success','Izvēlētajam objektam pasūtīts izvēlētais pakalpojums!');
                        Response::redirect('/abonents/pakalpojumi/pasutit');
                    }
                    else
                    {
                        Session::set_flash('error','Notikusi kļūda! Pakalpojums objektam netika pasūtīts.');
                        Response::redirect('/abonents/pakalpojumi/pasutit');
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
       
}
