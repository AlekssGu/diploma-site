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
                $get_client_number = Model_User::find_by('username', Input::post('client_number'));
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
                $getemail = Model_User::find_by('email', Input::post('email'));
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
                            $code = substr(md5(uniqid(mt_rand(), true)) , 0, 8); // unikāls kods
                            $new = new Model_Temp_User();
                            $new -> client_number = Input::post('client_number');
                            $new -> email = Input::post('email');
                            $new -> code = $code;
                            $new -> password = sha1(Input::post('password'));
                            $new -> messages = Input::post('messages'); // vai lietotājs vēlas saņemt vēstules

                                // Create an instance
                                $email = Email::forge();

                                // Set the from address
                                $email->from('gusevs.aleksandrs@gmail.com', 'IS PILSETAS UDENS');

                                // Set the to address
                                $email->to(Input::post('email'));

                                // Set a subject
                                $email->subject('Reģistrācijas apstiprināšana');
                                
                                // masīvs e-pasta ziņas datiem (kods, klienta numurs u.c.)
                                $email_data = array();
                                $email_data['code'] = $code;
                                $email_data['client_number'] = Input::post('client_number');
                                
                                $email->html_body(\View::forge('emails/regconfirm', $email_data));
                                
                            // Ja process veiksmīgs, tad paziņojam par to lietotājam
                            if($new -> save() && $email->send())
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
                    } catch(\EmailSendingFailedException $e)
                      {
                            Session::set_flash('error','Sūtīšana neizdevās: ' . $e);
                            Response::redirect('/user/register');
                      }
                      catch(\EmailValidationFailedException $e)
                      {
                            Session::set_flash('error','E-pasta validācija neizdevās: ' . $e);
                            Response::redirect('/user/register');
                      }
                      catch(Exception $exc)
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
                $temp_user = Model_Temp_User::find('all', array(
                        'where' => array(
                            array('code', Input::post('code')),
                        )
                    ));

                // Ja lietotājs ir atrasts
                if($temp_user)
                {
                    // Iegūst no objekta izveidošanas laiku
                    foreach ($temp_user as $user) {
                        $created = $user -> created_at;
                        $client_number = $user -> username;
                        $email = $user -> email;
                        $password = $user -> password;
                    }
                    // Pieskaita 2 dienas (vienāds ar 48h)
                    $created = strtotime('+2 days', $created);
                    
                    // Pārbauda, vai nav iztecējis 48h termiņš
                    if($created > Date::forge()->get_timestamp())
                    {
                        $create_process = Auth::create_user
                        (
                            $client_number, 
                            $email, 
                            $password, 
                            1, 
                            'Aleksandrs',
                            'Gusevs',
                            1,
                            'Bāriņu iela',
                            '4/6',
                            '28',
                            'Liepājas rajons',
                            'LV-3401',
                            '29826904',
                            'Y'
                        );
                        
                        if($create_process)
                        {
                            // Nav iztecējis termiņš
                            Session::set_flash('success','Reģistrācija apstiprināta! Tagad droši var sākt lietot sistēmu.');
                            //Response::redirect('/confirm'); 
                        }
                        
                    }
                    else
                    {
                        // Ir iztecējis termiņš
                        Session::set_flash('error','Reģistrācija nav apstiprināta! Reģistrācijas apstiprināšanas termiņš ir iztecējis (48 stundas).');
                        //Response::redirect('/confirm');
                    }
                }
                else
                {
                    Session::set_flash('error', 'Sistēmā nav lietotāja, kuram ir jūsu ievadītais kods!');
                    //Response::redirect('/confirm');
                }
            }

            // Ja dati nāk no e-pasta saites
            else if(Input::method()=='GET')
            {
                // Pārbauda, vai ir pagaidu lietotājs (tabulā temp_users), kuram ir ievadītais kods (code)
                $temp_user = Model_Temp_User::find('all', array(
                        'where' => array(
                            array('code', $code),
                        )
                    ));
                
                // Ja tāds lietotājs ir atrasts
                if($temp_user)
                {
                    // Iegūst no objekta izveidošanas laiku
                    foreach ($temp_user as $user) {
                        $created = $user -> created_at;
                    }
                    // Pieskaita 2 dienas (vienāds ar 48h)
                    $created = strtotime('+2 days', $created);
                    
                    // Pārbauda, vai nav iztecējis 48h termiņš
                    if($created > Date::forge()->get_timestamp())
                    {
                        Session::set_flash('success', 'Reģistrācija apstiprināta! Tagad droši var sākt lietot sistēmu.');
                        //Response::redirect('/confirm');
                    }
                    else
                    {
                        Session::set_flash('error','Reģistrācija nav apstiprināta! Reģistrācijas apstiprināšanas termiņš ir iztecējis (48 stundas).');
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
            $auth = Auth::instance();
            // Ja lietotājs jau ir autorizējies un mēģina vēlreiz autorizēties, 
            // tad vedam viņu uz sistēmas sākumu
            if(Auth::check()) {
                Response::redirect('/');
            }
            
            if(Input::method()=='POST' && Security::check_token())
            {
                    if ($auth->login(Input::post('username'), Input::post('password'))) {
                    //Session::set_flash('success', 'Tu esi autorizējies!');
                        Response::redirect('/');
                    } else {
                        Session::set_flash('error', var_dump($auth->login(Input::post('username'), Input::post('password'))) . Input::post('username') . ' ' . Input::post('password') . 'Diemžēl reģistrācija neveiksmīga! Ievadīta nepareiza ievades datu kombinācija.');
                    }
            }
            
                            
                $this->template->title="Lietotāja autorizācija - Pilsētas ūdens";
                $this->template->content = View::forge('connection/login');
            
        }
}
