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
                $this -> template -> title = "Sākums - Pilsētas ūdens";
                $this -> template -> content = View::forge('main/index');
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
