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
            
            if(Input::method() == 'POST' && Security::check_token())
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

                // Pārbauda, vai parole ir vismaz 5 simbolu gara
                if (mb_strlen(Input::post('password')) < 5)
                {
                    $error_count++;
                    $error_message.='<li>Parolei jābūt vismaz 5 simbolu garai</li>';
                }
                
                // Pārbauda, vai parole ir pietiekami sarežģīta
                if (!preg_match('/[A-Za-z]/', Input::post('password')) || !preg_match('/[0-9]/', Input::post('password')))
                {
                    $error_count++;
                    $error_message.='<li>Parolei jāsatur vismaz 1 burts un 1 cipars</li>';
                }
                
                // Parolēm ir jāsakrīt
                if (Input::post('password') != Input::post('secpassword'))
                {
                    $error_count++;
                    $error_message.='<li>Parolēm ir jāsakrīt</li>';
                }    

                // Pārbauda, vai sistēmā ir lietotājs ar šādu klienta numuru
                $get_client_number = Model_Isu_User::find_by('client_number', Input::post('client_number'));
                if($get_client_number)
                {
                    $error_count++;
                    $error_message.='<li>Lietotājs ar šādu klienta numuru jau ir reģistrēts sistēmā</li>';
                }

                // Pārbauda, vai sistēmā ir lietotājs ar šādu klienta numuru
                $get_client_number2 = Model_Temp_User::find_by('client_number', Input::post('client_number'));
                if($get_client_number2)
                {
                    $error_count++;
                    $error_message.='<li>Lietotājs ar šādu klienta numuru jau pastāv sistēmā, bet vēl nav apstiprinājis reģistrāciju</li>';
                }

                // Pārbauda, vai sistēmā ir lietotājs ar šādu e-pasta adresi
                $getemail = Model_Isu_User::find_by('email', Input::post('email'));
                if($getemail)
                {
                    $error_count++;
                    $error_message.='<li>Lietotājs ar šādu e-pasta adresi jau ir reģistrēts sistēmā</li>';
                }

                // Pārbauda, vai sistēmā ir lietotājs ar šādu e-pasta adresi
                $getemail2 = Model_Temp_User::find_by('email', Input::post('email'));
                if($getemail2)
                {
                    $error_count++;
                    $error_message.='<li>Lietotājs ar šādu e-pasta adresi jau pastāv sistēmā, taču nav apstiprinājis reģistrāciju</li>';
                }

                // Ja ir kļūdas, tad uzstāda kļūdas statusu un sagatavo paziņojumu lietotājam
                if ($error_count != 0){
                    //$result_status = 'error';
                            if($error_count == 1){
                                $result_message = 'Radusies viena kļūda: <br/><ul>'. $error_message . "</ul><br/>Lūdzu salabot kļūdu un mēģināt vēlreiz!";
                            }
                            else{
                                $result_message = 'Radušās ' .$error_count. ' kļūdas: <br/><ul>'. $error_message . "</ul><br/>Lūdzu salabot kļūdas un mēģināt vēlreiz!";
                            }
                     $data['result_message'] = $result_message;
                     $this -> template -> content = View::forge('connection/register', $data);
                } 
                // pretējā gadījumā veidojam lietotāju
                else 
                {
                    // try - catch bloks kļūdas pārtveršanai
                    try
                    {
                            $new = new Model_Temp_User();
                            $new -> client_number = Input::post('client_number');
                            $new -> email = Input::post('email');
                            $new -> code = substr(md5(uniqid(mt_rand(), true)) , 0, 8); // unikāls kods
                            $new -> password = Input::post('password');
                            $new -> messages = Input::post('messages'); // vai lietotājs vēlas saņemt vēstules

                            // Ja process veiksmīgs, tad paziņojam par to lietotājam
                            if($new -> save())
                            {
                                // Create an instance
                                $email = Email::forge();

                                // Set the from address
                                $email->from('registracija@udens.agusevs.com', 'Reģistrācija');

                                // Set the to address
                                $email->to('alex282@inbox.lv', 'Aleksandrs Gusevs');

                                // Set a subject
                                $email->subject('This is the subject');

                                // And set the body.
                                $email->body('This is my message');
                                
                                try{
                                    $email->send();
                                }
                                catch(\EmailSendingFailedException $e)
                                {
                                    Session::set_flash('error',$e);
                                    Response::redirect('/user/register');
                                }
                                catch(\EmailValidationFailedException $e)
                                {
                                    Session::set_flash('error',$e);
                                    Response::redirect('/user/register');
                                }
                                
                                Session::set_flash('success','Reģistrācija veiksmīga! Uz jūsu norādīto e-pastu tika nosūtīta reģistrācijas apstiprināšanas vēstule.');
                                Response::redirect('/user/register');
                            } 
                            // Paziņojam par neveiksmi
                            else
                            {
                                Session::set_flash('error','Reģistrācija neveiksmīga! Notikusi sistēmas iekšēja kļūda. Lūdzu, ziņojiet par šo kļūdu sistēmas administrācijai.');
                                Response::redirect('/user/register');
                            }   
                            
                    // ķer kļūdu un paziņo par to lietotājam 
                    } catch(Exception $exc)
                      {
                            Session::set_flash('error', "Notikusi sistēmas iekšēja kļūda! Lūdzu, ziņojiet par šo kļūdu administrācijai.");
                            Response::redirect('/user/register');
                      }
                } // kļūdas paziņojumu bloks
            } // POST bloks
                
                // Uzstāda lapas nosaukumu (title)
                $this -> template -> title = 'Reģistrācija - Pilsētas ūdens';
                
                // Ja nekas nav izmainījis šablona saturu un tas ir palicis tukšs, tad uzstādam tā vērtību
                if(empty($this -> template -> content))
                $this -> template -> content = View::forge('connection/register');
            
      }
}
