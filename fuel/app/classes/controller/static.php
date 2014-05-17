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
class Controller_Static extends Controller_Template
{
        // Ja kāds nevēlams cilvēks vēlas salauzt sistēmu, ievadot nepareizu adresi, sūtam viņu uz sākumlapu
	public function action_recent()
	{
                Response::redirect('/');
	}
        
        /**
	 * Funkcija: 3.3.5.3.	Ziņu lapas attēlošana (viesis, klients, darbinieks)
         * Identifikators: IS_STC_NEWS
	 *
         * Visi lietotāji var skatīt ziņu lapu, kur ir jaunākās ziņas un aprakstītas aktualitātes sakarā ar uzņēmuma darbību. 
	 */
	public function action_recent_news()
	{
                $data['news'] = DB::select()
                                ->from('news')
                                ->where('status','=','Publisks')
                                ->as_object()
                                ->execute()
                                ->as_array();
            
                $this -> template -> title = "Ziņas - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/recent/news',$data);
	}
        
        /**
	 * Funkcija: 3.3.5.8.	Pakalpojumu lapas attēlošana (viesis, klients, darbinieks)
         * Identifikators: IS_STC_SERVICES
	 *
         * Lietotāji var skatīt pieejamos pakalpojumus.
         * TODO: ierakstīt datubāzē pakalpojumus un izvadīt dinamiski
         * 
	 */
	public function action_services()
	{
                $this -> template -> title = "Pakalpojumi - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/services');
	}
        
        /**
	 * Funkcija: 3.3.5.10.	Par mums lapu attēlošana (viesis, klients, darbinieks)
         * Identifikators: IS_STC_ABOUT
	 *
         * Lietotāji var skatīt informāciju par uzņēmumu
         * 
	 */    
	public function action_about($page_name = null)
	{
            $data = array();
            $data['page_name'] = '';
            
            if($page_name=='rekviziti')
            {
                $data['page_name'] = 'rekviziti';
            }
            else if($page_name=='sertifikati')
            {
                $data['page_name'] = 'sertifikati';
            }
            else if($page_name=='struktura')
            {
                $data['page_name'] = 'struktura';
            }
            else if($page_name=='nozare')
            {
                $data['page_name'] = 'nozare';
            }
            else if($page_name=='vesture')
            {
                $data['page_name'] = 'vesture';
            }
            else if($page_name=='ekskursijas')
            {
                $data['page_name'] = 'ekskursijas';
            }
            else Response::redirect('/');
            
                $this -> template -> title = "Uzņēmuma darbība - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/about',$data);
	}
        // par mums sadaļas beidzas
        
        // palīdzības sadaļas
        public function action_help()
        {
            Response::redirect('/');
        }
        
        /**
	 * Funkcija: 3.3.5.12.	Sazināties ar uzņēmumu (viesis, klients)
         * Identifikators: IS_STC_CONTACT
	 *
         * Sistēmas lietotāji var sazināties ar uzņēmumu 
         * 
	 */    
        public function action_help_contact()
        {
            // Ja nav atrasta neviena tēma, tas nozīmē, ka izveidota jauna db un vajag ielikt vērtības
            $topics = Model_Topic::find('all');
            if(!$topics) 
            {
                $t1 = new Model_Topic(array('question' => 'Skaitītāju maiņa'));
                $t2 = new Model_Topic(array('question' => 'Jauns pieslēgums'));
                $t3 = new Model_Topic(array('question' => 'Esošs pieslēgums'));
                $t4 = new Model_Topic(array('question' => 'Rēķinu apmaksa'));
                $t5 = new Model_Topic(array('question' => 'Ūdeņu attīrīšana')); 
                
                $t1 -> save();
                $t2 -> save();
                $t3 -> save();
                $t4 -> save();
                $t5 -> save();
                
                $topics = Model_Topic::find('all');
            }
            
            //Padodamie dati skatam
            $data['topics'] = $topics;
            
            //Ja no formas tiek pievienots jauns jautājums
            if(Input::method()=='POST' && Security::check_token())
            {
                //Izveido jaunu ierakstu, piešķir vērtības
                $question = new Model_Question();
                $question -> topic_id = Input::post('topic');
                $question -> message = Input::post('comment');
                
                //Ja ir autorizējies, tad ieliek lietotāja id
                if(Auth::check()) {
                    $question -> user_id = Auth::get('id');
                }
                
                //Ja nav autorizējies, tad ir aizpildīti papildus lauki
                if(!Auth::check()) {
                    $question -> fullname = Input::post('fullname');
                    $question -> email = Input::post('email');
                }
               
                //Pārbauda, vai izdevies saglabāt, un paziņo par to lietotājam
                if($question -> save()) Session::set_flash('success','Ziņa saņemta! Mēs noteikti drīz sazināsimies ar tevi.');
                else Session::set_flash('error','Ziņa netika saņemta! Pamēģini vēlreiz.');
            }
            
                $this -> template -> title = "Sazināties ar uzņēmumu - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/help/contact',$data);
        }
        
        public function action_help_faq()
        {
                $this -> template -> title = "Biežāk uzdotie jautājumi (BUJ) - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/help/faq');
        }
        
        public function action_all_issues()
        {
            $data['emergencies'] = DB::select()
                        ->from('emergencies')
                        ->as_object()
                        ->execute()
                        ->as_array();
            
                $this -> template -> title = "Bojājumi, plānotie un neplānotie darbi - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/all_issues',$data);
            
        }
}
