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
        
        public function action_help_contact()
        {
                $this -> template -> title = "Sazināties ar uzņēmumu - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/help/contact');
        }
        
        public function action_help_faq()
        {
                $this -> template -> title = "Biežāk uzdotie jautājumi (BUJ) - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/help/faq');
        }
}
