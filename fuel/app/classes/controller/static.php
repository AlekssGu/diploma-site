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

	/**
	 * Galvenā lapas (index) funkcija
	 *
	 * Uzstāda: lapas nosaukums, saturs
         * 
	 */
	public function action_news()
	{
                $this -> template -> title = "Ziņas - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/news');
	}
        
	public function action_services()
	{
                $this -> template -> title = "Pakalpojumi - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/services');
	}
        
        // Par mums sadaļas:        
	public function action_about()
	{
                Response::redirect('/');
	}
        
	public function action_about_activity()
	{
                $this -> template -> title = "Uzņēmuma darbība - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/about/activity');
	}
        
	public function action_about_docs()
	{
                $this -> template -> title = "Normatīvie dokumenti - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/about/docs');
	}
        
	public function action_about_board()
	{
                $this -> template -> title = "Uzņēmuma pārvalde - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/about/board');
	}
        
	public function action_about_projects()
	{
                $this -> template -> title = "Projekti - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/about/projects');
	}
        
	public function action_about_history()
	{
                $this -> template -> title = "Uzņēmuma vēsture - Pilsētas ūdens";
                $this -> template -> content = View::forge('static/about/history');
	}
}
