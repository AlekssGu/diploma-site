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
class Controller_Main extends Controller_Template
{

	/**
	 * Galvenā lapas (index) funkcija
	 *
	 * Uzstāda: lapas nosaukums, saturs
         * 
	 */
	public function action_index()
	{          
            //Skatam padodamie dati
            $data = array();
            
            if(Auth::member(1))
            {
                $last_reading_query = DB::select('*')
                                    ->from('last_readings')
                                    ->where('client_id','=',Auth::get('id'));
                $last_reading = $last_reading_query -> as_object() -> execute() -> as_array();
                
                if(!empty($last_reading))
                {
                    $pre_last_rdn_query = DB::select('*')
                                        ->from('readings')
                                        ->where('meter_id','=',$last_reading[0]->id);
                    $pre_last_reading = $pre_last_rdn_query -> as_object() -> execute() -> as_array();

                    $last_reading[0] -> amount_since_last = round((int)$last_reading[0]->lead - (int)$pre_last_reading[0]->lead);    
                }
                
                $data['last_reading'] = $last_reading;
                
                $this -> template -> content = View::forge('main/user_index', $data);
            }
            else if(Auth::member(50)) 
                $this -> template -> content = View::forge('main/worker_index');
            else
                $this -> template -> content = View::forge('main/index');
            
                $this -> template -> title = "Sākums - Pilsētas ūdens";
	}

	/**
	 * 404 kļūdas lapas funkcija
	 *
         * Uzstāda: lapas nosaukums, saturs
         * 
	 */
	public function action_404()
	{
		$this -> template -> title = "Lapa nav atrasta - Pilsētas ūdens";
                $this -> template -> content = View::forge('main/404');
	}
}
