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

	public function action_recent()
	{
                Response::redirect('/');
	}
        
        /**
	 * Galvenā lapas (index) funkcija
	 *
	 * Uzstāda: lapas nosaukums, saturs
         * 
	 */
	public function action_recent_news()
	{
                $this -> template -> title = "Ziņas - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/recent/news');
	}
        
        /**
	 * Aktuālo bojājumu karte
	 *
	 * Uzstāda: lapas nosaukums, saturs
         * 
	 */
	public function action_recent_map()
	{
                $this -> template -> title = "Karte - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/recent/map');
	}
        
	public function action_services()
	{
                $this -> template -> title = "Pakalpojumi - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/services');
	}
        
        // Par mums sadaļas:        
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
