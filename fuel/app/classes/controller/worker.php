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
                                ->where('client_objects.client_id','=',$id); //Abonents
                
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
                    $user_id = Model_Object::find(Input::post('object_id'))->client_id;
                    Controller_Client::cre_cln_history($user_id,'Atslēgts pakalpojums');
                    return true;
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
    
    public function action_all_readings()
    {
        //Datu masīvs skatam
        $data = array();
        
        $query_last_readings = DB::select('*')->from('last_readings')->where('status','=','Iesniegts');
        $last_readings = $query_last_readings -> as_object() -> execute() -> as_array();

        //Sagatavo datus skatam
        $data['readings'] = $last_readings;
        
        $this -> template -> title = 'Iesniegtie rādījumi - IS Pilsētas ūdens';
        $this -> template -> content = View::forge('worker/all_readings', $data);
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
                $reading = Model_Reading::find(Input::post('reading_id'));
                $reading -> status = 'Atgriezts';
                
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
    
}
