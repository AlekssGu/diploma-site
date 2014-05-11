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
                                    ->where('client_id','=',Auth::get('id'))
                                    ->and_where('is_active','=','Y')
                                    ->order_by('rdn_id','DESC');
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

                //Iesniegtie pakalpojumi
                $data['service'] = DB::select('*')
                                    ->from('all_usr_requests')
                                    ->where('client_number','=',Auth::get('username'))
                                    ->order_by('request_id','DESC')
                                    ->limit(1)
                                    ->as_object()
                                    ->execute()
                                    ->as_array();
                
                $data['emergencies'] = DB::select()
                                        ->from('emergencies')
                                        ->as_object()
                                        ->execute()
                                        ->as_array();
                
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
        
        public function action_report_issue()
        {
            
            if(Input::method() == 'POST' && Security::check_token())
            {
                $issue = new Model_Emergency();
                $issue -> user_id = Auth::get('id');
                $issue -> lat = Input::post('latitude');
                $issue -> lon = Input::post('longitude');
                $issue -> notes = Input::post('notes');
                
                if($issue -> save())
                {
                    Session::set_flash('success', 'Bojājums veiksmīgi paziņots! Paldies!');
                    Response::redirect('/pazinot-par-bojajumu');
                }
                else 
                {
                    Session::set_flash('error', 'Neveiksme! Bojājums netika saglabāts.');
                    Response::redirect('/pazinot-par-bojajumu');
                }
            }
            
            $this -> template -> title = "Paziņot par bojājumu - Pilsētas ūdens";
            $this -> template -> content = View::forge('client/report_issue');
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
