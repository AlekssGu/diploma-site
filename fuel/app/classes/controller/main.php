<?php
/**
 * IS Pilsētas ūdens
 *
 * @package    udens
 * @version    0
 * @author     Aleksandrs Gusevs
 * @link       http://udens.agusevs.com
 */

class Controller_Main extends Controller_Template
{

        /**
	 * Funkcija: 3.3.5.1.	Galvenās lapas attēlošana (viesis, darbinieks, klients)
         * Identifikators: IS_STC_INDEX
	 *
         * Attēlo galveno lapu. Katrai grupai sava.
         * 
	 */
	public function action_index()
	{          
            //Skatam padodamie dati
            $data = array();
            
            //Ja lietotājs ir klients
            //IS_CLN_INDEX
            if(Auth::member(1))
            {
                //Atrod klienta pēdējo iesniegto mērījumu
                $last_reading_query = DB::select('*')
                                    ->from('last_readings')
                                    ->where('client_id','=',Auth::get('id'));
                $last_reading = $last_reading_query -> as_object() -> execute() -> as_array();
                
                //Ja tāds ir atrasts, tad atrod klienta pirmspēdējo rādījumu
                if(!empty($last_reading))
                {
                    $pre_last_rdn_query = DB::select('*')
                                        ->from('readings')
                                        ->where('meter_id','=',$last_reading[0]->id);
                    $pre_last_reading = $pre_last_rdn_query -> as_object() -> execute() -> as_array();

                    //Aprēķina patērēto ūdens daudzumu
                    $last_reading[0] -> amount_since_last = round((int)$last_reading[0]->lead - (int)$pre_last_reading[0]->lead);    
                }
                
                //Saglabā mainīgajā priekš skata
                $data['last_reading'] = $last_reading;
                
                $this -> template -> content = View::forge('main/user_index', $data);
            }
            //Ja lietotājs ir darbinieks, rādīt viņam citu index lapu
            //IS_WRK_INDEX 
            else if(Auth::member(50)) 
                $this -> template -> content = View::forge('main/worker_index');
            else
                $this -> template -> content = View::forge('main/index');
            
                $this -> template -> title = "Sākums - Pilsētas ūdens";
	}

        /**
	 * Funkcija: 3.3.5.2.	Kļūdas lapas attēlošana (viesis, darbinieks, klients)
         * Identifikators: IS_STC_ERROR
	 *
         * Sistēmas lietotāji var apskatīt kļūdas (404) lapu.
         * 
	 */
	public function action_404()
	{
		$this -> template -> title = "Lapa nav atrasta - Pilsētas ūdens";
                $this -> template -> content = View::forge('main/404');
	}
        
	/**
	 * Redmine
	 *
         * Nosūta lietotāju uz redmine lietotni no (udens.agusevs.com/redmine uz ...:8080)
         * 
	 */
        public function action_redmine()
        {
            Response::redirect('http://udens.agusevs.com:8080');
        }
}
