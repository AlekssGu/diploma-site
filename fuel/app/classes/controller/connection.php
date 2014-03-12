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
            
            // Ja tiek saņemti POST dati un valīds (valid) CSRF žetons (token)
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
                $get_client_number = Model_User::find_by('username', Input::post('client_number'));
                if($get_client_number==true && $get_client_number->is_confirmed=='Y')
                {
                    $error_count++;
                    $error_message.='<li>Lietotājs ar šādu klienta numuru jau ir reģistrēts sistēmā</li>';
                }

                // Pārbauda, vai sistēmā ir lietotājs ar šādu klienta numuru
                $get_client_number2 = Model_User::find_by('username', Input::post('client_number'));
                
                if($get_client_number2==true && $get_client_number2->is_confirmed=='N')
                {
                    $error_count++;
                    $error_message.='<li>Lietotājs ar šādu klienta numuru jau pastāv sistēmā, bet vēl nav apstiprinājis reģistrāciju</li>';
                }

                // Pārbauda, vai sistēmā ir lietotājs ar šādu e-pasta adresi
                $getemail = Model_User::find_by('email', Input::post('email'));
                if($getemail==true && $getemail->is_confirmed=='Y')
                {
                    $error_count++;
                    $error_message.='<li>Lietotājs ar šādu e-pasta adresi jau ir reģistrēts sistēmā</li>';
                }

                // Pārbauda, vai sistēmā ir lietotājs ar šādu e-pasta adresi
                $getemail2 = Model_User::find_by('email', Input::post('email'));
                if($getemail2==true && $getemail2->is_confirmed=='N')
                {
                    $error_count++;
                    $error_message.='<li>Lietotājs ar šādu e-pasta adresi jau pastāv sistēmā, taču nav apstiprinājis reģistrāciju</li>';
                }

                // Ja ir kļūdas, tad uzstāda kļūdas statusu un sagatavo paziņojumu lietotājam
                if ($error_count != 0){
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
                          //Sagatavo lietotāja unikālo kodu
                          $code = substr(md5(uniqid(mt_rand(), true)) , 0, 8);

                            //Izveido lietotāju
                            $create_user = Auth::create_user(
                                                Input::post('client_number'),
                                                Input::post('password'),
                                                Input::post('email'),
                                                1 
                                           );
                            //Ja nav iespējams izveidot lietotāju, tad iet uz exception bloku
                            if(!$create_user) throw new Exception('Nevarēja izveidot lietotāju!'); // todo
                            
                            //Ja izveidoja lietotāju, tad ievieto papildus vērtības
                            $new_user = Model_User::find($create_user);
                            $new_user -> city_id = 1; // Liepāja - todo
                            $new_user->unique_code = $code; 
                            if(isset($messages)) 
                                $new_user->is_messages = $messages;  
                            

                                // Izveido e-pasta instanci 
                                $email = Email::forge();
                                // Uzstāda "no kā" sūtīs e-pastu
                                $email->from('gusevs.aleksandrs@gmail.com', 'IS PILSETAS UDENS');
                                // Uzstāda "kam" sūtīs e-pastu
                                $email->to(Input::post('email'));
                                // Temats
                                $email->subject('Reģistrācijas apstiprināšana');
                                
                                // masīvs e-pasta ziņas datiem (kods, klienta numurs u.c.)
                                $email_data = array();
                                $email_data['code'] = $code;
                                $email_data['client_number'] = Input::post('client_number');
                                
                                // Izveido html ķermeni no skata, ko sūtīt
                                $email->html_body(\View::forge('emails/regconfirm', $email_data));
                                
                            // Ja process veiksmīgs, tad paziņojam par to lietotājam
                            if($new_user->save() && $email->send())
                            {
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
                    // E-pastu aizsūtīt nevarēja
                    } catch(\EmailSendingFailedException $e)
                      {
                            Session::set_flash('error','Sūtīšana neizdevās: ' . $e);
                            Response::redirect('/user/register');
                      }
                    // Netika ievadīts pareizs e-pasts
                      catch(\EmailValidationFailedException $e)
                      {
                            Session::set_flash('error','E-pasta validācija neizdevās: ' . $e);
                            Response::redirect('/user/register');
                      }
                    // Cita kļūda
                      catch(Exception $exc)
                      {
                            Session::set_flash('error',"Notikusi sistēmas iekšēja kļūda! Lūdzu, ziņojiet par šo kļūdu administrācijai.");
                            Response::redirect('/user/register');
                      }
                } // kļūdas paziņojumu bloks beidzas
            } // POST bloks beidzas
                
                // Uzstāda lapas nosaukumu (title)
                $this -> template -> title = 'Reģistrācija - Pilsētas ūdens';
                
                // Ja nekas nav izmainījis šablona saturu un tas ir palicis tukšs, tad uzstādam tā vērtību
                if(empty($this -> template -> content))
                $this -> template -> content = View::forge('connection/register');
            
      }
      
        /**
	 * Funkcija: 4.2.1.2. Lietotāja reģistrācijas apstiprināšana (viesis)
         * Identifikators: IS_USER_REGCONF
	 *
	 * Apstiprina lietotāja reģistrāciju sistēmā
         * 
	 */
      public function action_confirm($code = null) 
      {
          // Ja lietotājs jau ir autorizējies un mēģina ievadīt reģistrācijas 
            // kontroliera adresi, tad aizvedam viņu uz sākuma lapu
            if(Auth::check()) {
                Response::redirect('/');
            }
            
            // Ja dati nāk no formas
            if(Input::method()=='POST')
            {
                // Pārbauda, vai ir pagaidu lietotājs (tabulā temp_users), kuram ir ievadītais kods (code)
                $temp_user = Model_User::find_by('unique_code',Input::post('code'));
                // Paņem no objekta lietotāja id
                foreach ($temp_user as $data) {
                    $user_id = $data->id;
                }
                // Ja ir atrasts lietotāja id, tad atrod lietotāja objektu
                if(!empty($user_id)) $user_data = Model_User::find($user_id);
                else $user_data = false;
                
                // Ja lietotājs ir atrasts
                if($user_data)
                {
                    // Pieskaita 2 dienas izveidošanas datumam (vienāds ar 48h)
                    $created = strtotime('+2 days', $user_data->created_at);
                    
                    // Pārbauda, vai nav iztecējis 48h termiņš un vai lietotājs jau nav apstiprināts
                    if($created > Date::forge()->get_timestamp() && $user_data -> is_confirmed != 'Y')
                    {
                        //todo
                        $user_data -> name = 'Aleksandrs';
                        $user_data -> surname = 'Gusevs';
                        $user_data -> city_id = 1;
                        $user_data -> street = 'Bāriņu iela';
                        $user_data -> house = '4/6';
                        $user_data -> flat = '28';
                        $user_data -> district = 'Liepājas rajons';
                        $user_data -> post_code = 'LV-3401';
                        $user_data -> mobile_phone = '29826904';
                        $user_data -> is_active = 'Y';
                        $user_data -> is_confirmed = 'Y';

                        // Ja izdevies pielasīt datus no esošas sistēmas, tad paziņo par to lietotājam
                        if($user_data->save())
                        {
                            // Nav iztecējis termiņš
                            Session::set_flash('success','Reģistrācija ir apstiprināta! Tagad droši var sākt lietot sistēmu.');
                        }
                        else 
                        {
                            // Nav iztecējis termiņš
                            Session::set_flash('error','Reģistrācija nav apstiprināta! Ir notikusi sistēmas iekšēja kļūda.');
                        }
                    }
                    else
                    {
                        // Ir iztecējis termiņš
                        Session::set_flash('error','Reģistrācija nav apstiprināta! Reģistrācijas apstiprināšanas termiņš ir iztecējis (48 stundas) vai lietotājs jau ir apstiprināts!');
                    }
                }
                else
                {
                    Session::set_flash('error', 'Sistēmā nav lietotāja, kuram ir jūsu ievadītais kods!');
                }
            }

            // Ja dati nāk no e-pasta saites ar GET parametru
            else if(Input::method()=='GET')
            {
                // Pārbauda, vai ir pagaidu lietotājs (tabulā temp_users), kuram ir ievadītais kods (code)
                $temp_user = Model_User::find_by('unique_code',$code);
                // Paņem no objekta lietotāja id
                foreach ($temp_user as $data) {
                    $user_id = $data->id;
                }
                // Ja ir atrasts lietotāja id, tad atrod lietotāja objektu
                if(!empty($user_id)) $user_data = Model_User::find($user_id);
                else $user_data = false;

                
                // Ja lietotājs ir atrasts
                if($user_data)
                {
                    // Pieskaita 2 dienas (vienāds ar 48h)
                    $created = strtotime('+2 days', $user_data->created_at);
                    
                    // Pārbauda, vai nav iztecējis 48h termiņš
                    if($created > Date::forge()->get_timestamp() && $user_data -> is_confirmed != 'Y')
                    {
                        $user_data -> name = 'Aleksandrs';
                        $user_data -> surname = 'Gusevs';
                        $user_data -> city_id = 1;
                        $user_data -> street = 'Bāriņu iela';
                        $user_data -> house = '4/6';
                        $user_data -> flat = '28';
                        $user_data -> district = 'Liepājas rajons';
                        $user_data -> post_code = 'LV-3401';
                        $user_data -> mobile_phone = '29826904';
                        $user_data -> is_active = 'Y';
                        $user_data -> is_confirmed = 'Y';
                        
                        if($user_data->save())
                        {
                            // Nav iztecējis termiņš
                            Session::set_flash('success','Reģistrācija apstiprināta! Tagad droši var sākt lietot sistēmu.');
                            //Response::redirect('/confirm'); 
                        }
                    }
                    else
                    {
                        // Ir iztecējis termiņš
                        Session::set_flash('error','Reģistrācija nav apstiprināta! Reģistrācijas apstiprināšanas termiņš ir iztecējis (48 stundas) vai lietotājs jau ir apstiprināts!');
                        //Response::redirect('/confirm');
                    }
                }
                else
                {
                    Session::set_flash('error', 'Sistēmā nav lietotāja, kuram ir jūsu ievadītais kods!');
                    //Response::redirect('/confirm');
                }
            }
            else
            {
                $this -> template -> content = View::forge('connection/confirm');
            }
            
                if(empty($this -> template -> content))
                $this -> template -> content = View::forge('connection/confirm');
                $this -> template -> title = 'Reģistrācijas apstiprināšana - Pilsētas ūdens';
      }
      
        /**
	 * Funkcija: 4.2.1.4. Lietotāja autorizācija (viesis)
         * Identifikators: IS_USER_LOGIN
	 *
	 * Autorizē lietotāju sistēmā
         * 
	 */
        public function action_login()
        {
            // Ja lietotājs jau ir autorizējies un mēģina vēlreiz autorizēties, 
            // tad vedam viņu uz sistēmas sākumu
            if(Auth::check()) {
                Response::redirect('/');
            }
            
            // Ja tiek saņemti POST dati un CSRF žetons (token)
            if(Input::method()=='POST' && Security::check_token())
            {
                    if (Auth::login(Input::post('username'),Input::post('password'))) {
                        Session::set_flash('success', 'Autorizācija veiksmīga!');
                        Response::redirect('/');
                    } else {
                        Session::set_flash('error', 'Diemžēl autorizācija neveiksmīga! Ievadīta nepareiza ievades datu kombinācija.');
                    }
            }
            
                $this->template->title="Lietotāja autorizācija - Pilsētas ūdens";
                $this->template->content = View::forge('connection/login');
            
        }
        //todo
        public function action_logout()
        {
            Auth::logout();
            Response::redirect('/');
        }
}
