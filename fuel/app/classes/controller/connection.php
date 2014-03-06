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
 * Nodaļa: 4.2.1. Lietotāju pieslēgšanās sistēmai
 *
 * Sistēmas autentifikācijas, autorizācijas un reģistrācijas kontrolieris
 * 
 */
class Controller_Connection extends Controller_Template
{

	/**
	 * Funkcija: 4.2.1.1. Lietotāju reģistrēšana (viesis)
         * Identifikators: IS_USER_REGISTER
	 *
	 * Reģistrē lietotāju sistēmā
         * 
	 */
	public function action_register()
	{
            // Ja lietotājs jau ir autorizējies un mēģina ievadīt reģistrācijas 
            // kontroliera adresi, tad aizvedam viņu uz sākuma lapu
            if(Auth::check()) {
                Response::redirect('/');
            }
            // Pārbauda, vai saņemam korektus POST datus un CSRF žetonu (token)
            if (Input::method() == 'POST' && Security::check_token()) 
            {
                // Nodefinējam un inicializējam parametrus
                $error_message=""; // Kļūdas ziņojuma daļa
                $error_count = 0; // Kļūdu skaits
                $result_status = ""; // Reģistrācijas statuss {success/error}
                $result_message = ""; // Gala paziņojums lietotājam
            
            // Ja nav ievadīts klienta numurs
            if (Input::post('client_number')==''){
                $error_count++;
                $error_message.='<li>Nav ievadīts klienta numurs</li>'; 
            
            }
            
            // Pārbauda, vai ir ievadīts korekts e-pasts
            if (!filter_var(Input::post('email'), FILTER_VALIDATE_EMAIL)){
                $error_count++;
                $error_message.='<li>Nav ievadīts korekts e-pasts</li>';
            }
            
            // Pārbauda, vai parole ir pietiekami sarežģīta un ir vismaz 5 simbolu gara
            if (!preg_match('/[A-Za-z]/', Input::post('password')) || !preg_match('/[0-9]/', Input::post('password')) || !mb_strlen(Input::post('password')) > 4)
            {
                $error_count++;
                $error_message.='<li>Parolei jāsatur vismaz 1 burts un 1 cipars, kā arī tai jābūt vismaz 5 simbolu garai</li>';
            }

            // Parolēm ir jāsakrīt
            if (Input::post('password')!= Input::post('secpassword')){
                $error_count++;
                $error_message.='<li>Parolēm ir jāsakrīt</li>';
            }    
            
            // Ja ir kļūdas, tad uzstāda kļūdas statusu un sagatavo paziņojumu lietotājam
            if ($error_count != 0){
                $result_status = 'error';
                        if($error_count == 1){
                            $result_message = 'Radusies viena kļūda: <br/><ul>'. $error_message . "</ul><br/>Lūdzu salabot kļūdu un mēģināt vēlreiz!";
                        }
                        else{
                            $result_message = 'Radušās ' .$error_count. ' kļūdas: <br/><ul>'. $error_message . "</ul><br/>Lūdzu salabot kļūdas un mēģināt vēlreiz!";
                        }
                 $data['result_message'] = $result_message;
                 $this -> template -> content = View::forge('connection/register', $data);
            } else $this -> template -> content = View::forge('connection/register');
            
            
            } else $this -> template -> content = View::forge('connection/register');
            
                $this -> template -> title = "Reģistrācija - Pilsētas ūdens";
                
	}
}
