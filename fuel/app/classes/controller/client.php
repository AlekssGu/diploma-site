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
                $userdata = Model_Userinfo::find($user->userinfo_id); // klienta dati
                $useraddr1 = Model_Address::find($userdata -> address_id, array(
                                            'where' => array(
                                                array('addr_type', 'D')
                                            )
                                )); // deklarētā adrese
                $useraddr2 = Model_Address::find($userdata -> secondary_addr_id, array(
                                            'where' => array(
                                                array('addr_type', 'F')
                                            )
                                )); // faktiskā adrese
                $city1 = Model_City::find($useraddr1->city_id); // deklarētās adreses pilsēta
                $city2 = Model_City::find($useraddr2->city_id); // faktiskās adreses pilsēta
                $cities = Model_City::find('all');
                
                // klienta objekti (dzīvokļi, mājas u.c. ēkas, kur uzstādīti skaitītāji)
                $query = DB::select(array('objects.id', 'object_id'),
                                    'objects.name')
                            -> from('objects')
                            -> join('addresses')
                            -> on('addresses.id', '=', 'objects.address_id')
                            -> where('objects.client_id',Auth::get('id'))
                            -> and_where('addresses.addr_type','=','O');
                
                $objects = $query->as_object()->execute()->as_array();
                
                $data['cities'] = $cities;
                $data['objects'] = $objects;
                $data['client_number'] = $user -> username;
                $data['fullname'] = $userdata -> name . ' ' . $userdata -> surname;
                $data['email'] = $user -> email;
                $data['phone'] = $userdata -> mobile_phone;
                // deklarētā adrese
                $data['primary_address'] = $useraddr1 -> street . ' ' . $useraddr1 -> house . ' - ' . $useraddr1 -> flat . ', ' . $useraddr1 -> district . ', ' . $city1->city_name . ', ' . $useraddr1 -> post_code;
                // faktiskā adrese
                $data['secondary_address'] = $useraddr2 -> street . ' ' . $useraddr2 -> house . ' - ' . $useraddr2 -> flat . ', ' . $useraddr2 -> district . ', ' . $city2->city_name . ', ' . $useraddr2 -> post_code;
                
                $this -> template -> title = "Klienta informācija - Pilsētas ūdens";
                $this -> template -> content = View::forge('client/client',$data);
            }
            else
            {
                Response::redirect('/');
            }
	}
        
        public function action_add_object()
        {
            if(Auth::check())
            {
                if(Input::method()=='POST' && Security::check_token())
                {
                    $new_address = new Model_Address();
                    $new_address -> client_id = Auth::get('id');
                    $new_address -> city_id = Input::post('city_id');
                    $new_address -> street = Input::post('street');
                    $new_address -> house = Input::post('house');
                    $new_address -> flat = Input::post('flat');
                    $new_address -> district = Input::post('district');
                    $new_address -> post_code = Input::post('post_code');
                    $new_address -> addr_type = 'O';
                    
                    if($new_address -> save())
                    {
                        $new_object = new Model_Object();
                        $new_object -> client_id = Auth::get('id');
                        $new_object -> address_id = $new_address->id;
                        $new_object -> name = Input::post('name');
                        $new_object -> notes = Input::post('notes');
                        
                        if($new_object -> save())
                        {
                            Session::set_flash('success','Objekts pievienots!');
                            Response::redirect('/klients');
                        }
                        else
                        {
                            $rollback = Model_Address::find($new_address->id);
                            $rollback->delete();
                            
                            Session::set_flash('error','Neveiksme! Nebija iespējams izveidot jaunu objektu.');
                            Response::redirect('/klients');
                        }
                    }
                    else
                    {
                        Session::set_flash('error','Neveiksme! Nebija iespējams izveidot jaunu adresi.');
                        Response::redirect('/klients');
                    }
                    
                }
                else 
                {
                    Response::redirect('/');
                }
            }
            else
            {
                Response::redirect('/');
            }
        }
        
        public function action_all_objects()
        {
            if(Auth::check())
            {
                $data = array();
                
                $useer = Controller_Connection::get_user_data('12345678');
                
                var_dump($useer);
                
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
                            -> and_where('addresses.addr_type','=','O');
                
                $result = $query -> as_object() -> execute() -> as_array();
                
                foreach ($result as $value => $key) {
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
                
                        $query_meters = DB::select(array('meters.id', 'meter_id'),
                                                        'meters.*') 
                                                -> from('meters')
                                                -> join('objects')
                                                -> on('objects.id', '=', 'meters.object_id')
                                                -> where('objects.client_id',Auth::get('id'))
                                                -> and_where('objects.id','=',$id);
                        
                        $result_meters = $query_meters -> as_object() -> execute() -> as_array();
                        
                        $data['objects'] = $result_objects;
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
            if(Auth::check())
            {
                if(Input::method()=='POST' && Security::check_token())
                {
                    
                    $new_meter = new Model_Meter();
                    $new_meter -> object_id = Input::post('object_id');
                    $new_meter -> date_from = Input::post('date_from');
                    $new_meter -> date_to = Input::post('date_to');
                    $new_meter -> meter_type = Input::post('meter_type');
                    $new_meter -> water_type = 'dummy';
                    $new_meter -> worker_id = 1;
                    $new_meter -> meter_number = Input::post('number');
                    $new_meter -> meter_model = 'dummy';
                    $new_meter -> meter_lead = Num::format(Input::post('lead'), '00000000');
                    
                    if($new_meter -> save())
                    {
                        Session::set_flash('success','Skaitītājs pievienots!');
                        Response::redirect('/klients/objekti/apskatit/'.Input::post('object_id'));
                    }
                    else
                    {
                        Session::set_flash('error','Neveiksme! Neizdevās pievienot skaitītāju!');
                        Response::redirect('/klients/objekti/apskatit/'.Input::post('object_id'));
                    }
                }
                else
                {
                    Response::redirect('/klients/objekti/apskatit/'.Input::post('object_id'));
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
                $this -> template -> title = "Skaitītāju rādījumi - Pilsētas ūdens";
                $this -> template -> content = View::forge('client/add_reading');
            }
            else
            {
                Response::redirect('/');
            }
        }
       
}
