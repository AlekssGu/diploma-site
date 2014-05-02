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
	 * Funkcija: 3.3.1.1. Lietotāju reģistrēšana (viesis)
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
                $view_data = array();
                $error_message=""; // Kļūdas ziņojuma daļa
                $error_count = 0; // Kļūdu skaits
                $result_status = ""; // Reģistrācijas statuss {success/error}
                $result_message = ""; // Gala paziņojums lietotājam
            
                // Ja nav ievadīts klienta numurs
                if (Input::post('client_number')==''){
                    $error_count++;
                    $error_message.='<li>Nav ievadīts klienta numurs</li>'; 

                }
                
                // Ja nav ievadīts klienta numurs
                if (mb_strlen(Input::post('client_number'))!=8){
                    $error_count++;
                    $error_message.='<li>Klienta numuram jāsastāv no 8 simboliem</li>'; 

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
                // Paņem no objekta lietotāja id
                foreach ($get_client_number as $data) {
                    $user_id = $data->id;
                }
                // Ja ir atrasts lietotāja id, tad atrod lietotāja objektu
                if(!empty($user_id)) $user_data = Model_User::find($user_id);
                else $user_data = false;
                
                if($user_data == true && $user_data->is_confirmed=='Y')
                {
                        $error_count++;
                        $error_message.='<li>Lietotājs ar šādu klienta numuru jau ir reģistrēts sistēmā</li>';
                }

                // Pārbauda, vai sistēmā ir lietotājs ar šādu klienta numuru
                $get_client_number2 = Model_User::find_by('username', Input::post('client_number'));
               // Paņem no objekta lietotāja id
                foreach ($get_client_number2 as $data) {
                    $user_id = $data->id;
                }
                // Ja ir atrasts lietotāja id, tad atrod lietotāja objektu
                if(!empty($user_id)) $user_data = Model_User::find($user_id);
                else $user_data = false;
                
                if($user_data == true && $user_data->is_confirmed == 'N')
                {
                        $error_count++;
                        $error_message.='<li>Lietotājs ar šādu klienta numuru jau pastāv sistēmā, bet vēl nav apstiprinājis reģistrāciju</li>';
                }

                // Pārbauda, vai sistēmā ir lietotājs ar šādu e-pasta adresi
                $getemail = Model_User::find_by('email', Input::post('email'));
                // Paņem no objekta lietotāja id
                foreach ($getemail as $data) {
                    $user_id = $data->id;
                }
                // Ja ir atrasts lietotāja id, tad atrod lietotāja objektu
                if(!empty($user_id)) $user_data = Model_User::find($user_id);
                else $user_data = false;
                
                if($user_data == true && $user_data->is_confirmed == 'Y')
                {
                        $error_count++;
                        $error_message.='<li>Lietotājs ar šādu e-pasta adresi jau ir reģistrēts sistēmā</li>';
                }

                // Pārbauda, vai sistēmā ir lietotājs ar šādu e-pasta adresi
                $getemail2 = Model_User::find_by('email', Input::post('email'));
                // Paņem no objekta lietotāja id
                foreach ($getemail as $data) {
                    $user_id = $data->id;
                }
                // Ja ir atrasts lietotāja id, tad atrod lietotāja objektu
                if(!empty($user_id)) $user_data = Model_User::find($user_id);
                else $user_data = false;
                
                if($user_data==true && $user_data->is_confirmed=='N')
                {
                    $error_count++;
                    $error_message.='<li>Lietotājs ar šādu e-pasta adresi jau pastāv sistēmā, bet vēl nav apstiprinājis reģistrāciju</li>';
                }

                // Ja ir kļūdas, tad uzstāda kļūdas statusu un sagatavo paziņojumu lietotājam
                if ($error_count != 0){
                            if($error_count == 1){
                                $result_message = 'Radusies viena kļūda: <br/><ul>'. $error_message . "</ul><br/>Lūdzu salabot kļūdu un mēģināt vēlreiz!";
                            }
                            else{
                                $result_message = 'Radušās ' .$error_count. ' kļūdas: <br/><ul>'. $error_message . "</ul><br/>Lūdzu salabot kļūdas un mēģināt vēlreiz!";
                            }
                            
                     $view_data['result_message'] = $result_message;
                     $this -> template -> content = View::forge('connection/register', $view_data);
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
                            $new_user->unique_code = $code; 
                            if(Input::post('messages')!='') 
                                $new_user->is_messages = Input::post('messages');  

                                // Izveido e-pasta instanci 
                                $email = Email::forge();
                                // Uzstāda "no kā" sūtīs e-pastu
                                $email->from('pilsetasudens@gmail.com', 'IS PILSETAS UDENS');
                                // Uzstāda "kam" sūtīs e-pastu
                                $email->to(Input::post('email'));
                                // Temats
                                $email->subject('Reģistrācijas apstiprināšana');
                                
                                // masīvs e-pasta ziņas datiem (kods, klienta numurs u.c.)
                                $email_data = array();
                                $email_data['code'] = $code;
                                
                                // Izveido html ķermeni no skata, ko sūtīt
                                $email->html_body(\View::forge('emails/regconfirm', $email_data));
                                
                            // Ja process veiksmīgs, tad paziņojam par to lietotājam
                            if($new_user->save() && $email->send())
                            {
                                Session::set_flash('success','Reģistrācija veiksmīga! Uz jūsu norādīto e-pastu tika nosūtīta reģistrācijas apstiprināšanas vēstule.');
                                Response::redirect('/abonents/registreties');
                            } 
                            // Paziņojam par neveiksmi
                            else
                            {
                                Session::set_flash('error','Reģistrācija neveiksmīga! Notikusi sistēmas iekšēja kļūda. Lūdzu, ziņojiet par šo kļūdu sistēmas administrācijai.');
                                Response::redirect('/abonents/registreties');
                            }   
                            
                    // ķer kļūdu un paziņo par to lietotājam 
                    // E-pastu aizsūtīt nevarēja
                    } catch(\EmailSendingFailedException $e)
                      {
                            Session::set_flash('error','Notikusi sistēmas iekšēja kļūda! Nav iespējams nosūtīt e-pastu!');
                            Response::redirect('/abonents/registreties');
                      }
                    // Netika ievadīts pareizs e-pasts
                      catch(\EmailValidationFailedException $e)
                      {
                            Session::set_flash('error','E-pasts netika nosūtīts! Ievadīts nekorekts e-pasts.');
                            Response::redirect('/abonents/registreties');
                      }
                    // Cita kļūda
                      catch(Exception $exc)
                      {
                            Session::set_flash('error',"Notikusi sistēmas iekšēja kļūda! Lūdzu, ziņojiet par šo kļūdu administrācijai.");
                            Response::redirect('/abonents/registreties');
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
     * Funkcija: 3.3.1.4. Lietotāja datu izgūšana (sistēma)
     * Identifikators: GLOBAL_GET_USR_DATA
     *
     * Saņem datus no esošas sistēmas
     * 
     */
      static function get_user_data($data)
      {
          $user_confirmed = false;
          
            //Atrod lietotāju esošajā sistēmā, kuram ir padotais klienta numurs
            $query_ext_user = DB::select() 
                        -> from('external_users')
                        -> where('external_users.client_number','=',$data->username)
                        -> limit(1);
            
            $ext_data = $query_ext_user -> as_object() -> execute() -> as_array();

            //Atrod lietotāju jaunajā sistēmā, kuram ir padotais klienta numurs
            $query_local_user = DB::select() 
                        -> from('users')
                        -> where('users.username','=',$data->username)
                        -> limit(1);
            
            $local_user = $query_local_user -> as_object() -> execute() -> as_array();
            
            //Ja abi atrasti, tad atrod nepieciešamos datus
            if($ext_data && $local_user)
            {
                // deklarētā pilsēta
                $query_pri_city_id = DB::select('id') 
                                 -> from('cities')
                                 -> where('cities.city_name','=',$ext_data[0] -> pri_city)
                                 -> limit(1);
                $pri_city_id_obj = $query_pri_city_id -> as_object() -> execute() -> as_array();

                // faktiskā pilsēta
                $query_sec_city_id = DB::select('id') 
                                 -> from('cities')
                                 -> where('cities.city_name','=',$ext_data[0] -> sec_city)
                                 -> limit(1);
                $sec_city_id_obj = $query_sec_city_id -> as_object() -> execute() -> as_array();

                $ext_data[0]->pri_city_id = $pri_city_id_obj[0]->id;
                $ext_data[0]->sec_city_id = $sec_city_id_obj[0]->id;


                // Deklarētā adrese
                $pri_addr = Model_Address::forge();
                $pri_addr -> client_id = $ext_data[0] -> id;
                $pri_addr -> city_id = $ext_data[0]->pri_city_id;
                $pri_addr -> street = $ext_data[0] -> pri_street;
                $pri_addr -> house = $ext_data[0] -> pri_house;
                $pri_addr -> flat = $ext_data[0] -> pri_flat;
                $pri_addr -> district = $ext_data[0] -> pri_district;
                $pri_addr -> post_code = $ext_data[0] -> pri_postcode;
                $pri_addr -> addr_type = 'D';

                // Faktiskā adrese
                $sec_addr = Model_Address::forge();
                $sec_addr -> client_id = $ext_data[0] -> id;
                $sec_addr -> city_id = $ext_data[0]->sec_city_id;
                $sec_addr -> street = $ext_data[0] -> sec_street;
                $sec_addr -> house = $ext_data[0] -> sec_house;
                $sec_addr -> flat = $ext_data[0] -> sec_flat;
                $sec_addr -> district = $ext_data[0] -> sec_district;
                $sec_addr -> post_code = $ext_data[0] -> sec_postcode;
                $sec_addr -> addr_type = 'F';

                //Ja abas adreses ir izveidotas
                if($pri_addr->save() && $sec_addr->save())
                {
                    // Klienta dati
                    $person_data = Model_Person::forge();
                    $person_data -> address_id = $pri_addr->id;
                    $person_data -> secondary_addr_id = $sec_addr->id;
                    $person_data -> name = $ext_data[0] -> name;
                    $person_data -> surname = $ext_data[0] -> surname;
                    $person_data -> person_code = $ext_data[0] -> person_code;
                    $person_data -> client_number = $ext_data[0] -> client_number;
                    $person_data -> mobile_phone = $ext_data[0] -> mobile_phone;
                    $person_data -> person_type = 'F';

                    //Ja izdevies saglabāt personas datus
                    if($person_data->save())
                    {
                        $data -> person_id = $person_data->id;
                        $data -> is_active = 'Y';
                        $data -> is_confirmed = 'Y';
                        
                        //Ja izdevies saglabāt lietotāja datus
                        if($data -> save())
                        {
                            $user_confirmed = true;                        
                        }
                    }
                }
            }
            else $user_confirmed = false;

            return $user_confirmed;
      }
      
        /**
	 * Funkcija: 3.3.1.2. Lietotāja reģistrācijas apstiprināšana (viesis)
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
            if(Input::method()=='POST' && Security::check_token())
            {
                // Ja ievadīts tukšs kods, tad atgriežam lietotāju atpakaļ
                if(Input::post('code') == "") Response::redirect('/apstiprinat/post');
                
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
                        
                        $user_created = Controller_Connection::get_user_data($user_data);
                        

                        // Ja izdevies pielasīt datus no esošas sistēmas, tad paziņo par to lietotājam
                        if($user_created)
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
            else if(Input::method()=='GET' && strtolower ($code) != 'post')
            {
                // Pārbauda, vai ir pagaidu lietotājs, kuram ir ievadītais kods (code)
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
                    // Pieskaita 2 dienas izveidošanas datumam (vienāds ar 48h)
                    $created = strtotime('+2 days', $user_data->created_at);
                    
                    // Pārbauda, vai nav iztecējis 48h termiņš un vai lietotājs jau nav apstiprināts
                    if($created > Date::forge()->get_timestamp() && $user_data -> is_confirmed != 'Y')
                    {

                         $user_created = Controller_Connection::get_user_data($user_data);
                        
                        // Ja izdevies pielasīt datus no esošas sistēmas, tad paziņo par to lietotājam
                        if($user_created)
                        {
                            // Ir apstiprināts un viss kārtībā
                            Session::set_flash('success','Reģistrācija ir apstiprināta! Tagad droši var sākt lietot sistēmu.');
                        }
                        else 
                        {
                            // Nav apstiprināts
                            Session::set_flash('error','Reģistrācija nav apstiprināta! Ir notikusi sistēmas iekšēja kļūda.');
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
	 * Funkcija: 3.3.1.5. Lietotāja autorizācija (viesis)
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
                // var autorizēties gan ar klienta numuru, gan ar e-pastu

                // meklē lietotāju pēc klienta numura
                $user_name = Model_User::find_by('username', Input::post('username'));
                // Paņem no objekta lietotāja id
                foreach ($user_name as $data) {
                    $user_id = $data->id;
                }
                
                // ja tukšs, tad meklējam pēc e-pasta
                if(empty($user_name)) 
                {
                    // meklē lietotāju pēc e-pasta
                    $user_email = Model_User::find_by('email', Input::post('username'));
                    // Paņem no objekta lietotāja id
                    foreach ($user_email as $data) {
                        $user_id = $data->id;
                    }
                }
                
                // Ja ir atrasts lietotāja id, tad atrod lietotāja objektu
                if(!empty($user_id)) $user_data = Model_User::find($user_id);
                else $user_data = false;

                // ja ir atrasts lietotājs
                if($user_data) 
                {
                    // Lietotājs būs neaktīvs, ja nav vēl apstiprināts
                    if($user_data->is_active == 'N' && $user_data->is_confirmed == 'N')
                    {
                        Session::set_flash('error', 'Autorizācija neveiksmīga! '
                                . 'Lietotājam ir jāapstiprina reģistrācija. '
                                . 'Lūdzu, pārbaudi savu e-pastu, tur noteikti jābūt vēstulei par to. '
                                . 'Spied <a href="/abonents/izsutit/'.$user_data->id.'">šeit</a>, lai izsūtītu apstiprināšanas kodu vēlreiz.');
                        
                        Response::redirect('/abonents/pieslegties');
                    }
                    
                    // Lietotājs bloķēts (administrators vai darbinieks bloķējis)
                    else if ($user_data->is_active == 'N')
                    {
                        Session::set_flash('error', 'Autorizācija neveiksmīga! Diemžēl tavs lietotāja konts ir bloķēts!');
                        Response::redirect('/abonents/pieslegties');
                    }
                    
                    // Viss kārtībā, var autorizēt lietotāju
                    else
                    {
                        // Ir iziets cauri pārbaudēm un var veikt autorizāciju
                        if (Auth::login(Input::post('username'),Input::post('password'))) 
                        {
                            //Session::set_flash('success', 'Autorizācija veiksmīga!');
                            Response::redirect('/');
                        } 
                        // Autorizēt nevarēja - nepareizi dati
                        else 
                        {
                            Session::set_flash('error', 'Diemžēl autorizācija neveiksmīga! Ievadīta nepareiza ievades datu kombinācija.');
                        }
                    }
                }
                // ja nav atrasts lietotājs
                else
                {
                    Session::set_flash('error', 'Autorizācija neveiksmīga! Šāds lietotājs sistēmā nepastāv.');
                    Response::redirect('/abonents/pieslegties');
                }
            }
            
                $this->template->title="Lietotāja autorizācija - Pilsētas ūdens";
                $this->template->content = View::forge('connection/login');
        }
        
        /**
	 * Funkcija: 3.3.1.6.	Lietotāja atteikšanās (klients, darbinieks, administrators)
         * Identifikators: IS_USER_LOGOUT
	 *
	 * Atslēdz lietotāju no sistēmas
         * 
	 */
        public function action_logout()
        {
            // Atslēdz autorizēto lietotāju no sistēmas un sūta uz galveno lapu
            Auth::logout();
            Response::redirect('/');
        }
        
        /**
	 * Funkcija: 3.3.1.7.	Lietotāja paroles atjaunošana (klients)
         * Identifikators: IS_USER_FORGOT
	 *
	 * Nosūta lietotājam e-pastu ar iespēju mainīt paroli
         * 
	 */
        public function action_forgot()
        {
            // Ja tiek saņemti POST dati un CSRF žetons (token)
            if(Input::method()=='POST' && Security::check_token())
            {
                // Ja ir saņemts korekts e-pasts
                if(filter_var(Input::post('email'), FILTER_VALIDATE_EMAIL))
                {   
                    // Meklē lietotāju pēc e-pasta
                    $user = Model_User::find_by('email',Input::post('email'));
                    
                    // Paņem no objekta lietotāja id
                    foreach ($user as $data) {
                        $user_id = $data->id;
                    }
                    // Ja ir atrasts lietotāja id, tad atrod lietotāja objektu
                    if(!empty($user_id)) $user_data = Model_User::find($user_id);
                    else $user_data = false;
                    
                    if($user_data) 
                    {
                        // Lietotājs būs neaktīvs, ja nav vēl apstiprināts
                        if($user_data->is_active == 'N' && $user_data->is_confirmed == 'N')
                        {
                            Session::set_flash('error', 'Lietotājam ir jāapstiprina reģistrācija. '
                                    . 'Lūdzu, pārbaudi savu e-pastu, tur noteikti jābūt vēstulei par to. '
                                    . 'Spied <a href="/abonents/izsutit/'.$user_data->id.'">šeit</a>, lai izsūtītu apstiprināšanas kodu vēlreiz.');

                            Response::redirect('/abonents/aizmirsta-parole');
                        }

                        // Lietotājs bloķēts (administrators vai darbinieks bloķējis)
                        else if ($user_data->is_active == 'N')
                        {
                            Session::set_flash('error', 'Neveiksme! Diemžēl tavs lietotāja konts ir bloķēts!');
                            Response::redirect('/abonents/aizmirsta-parole');
                        }

                        // Viss kārtībā, var veikt darbības
                        else
                        {
                            //Sagatavo lietotāja unikālo kodu
                            $code = substr(md5(uniqid(mt_rand(), true)) , 0, 8);
                            $user_data->unique_code = $code;
                            $user_data->save();
                            
                            // Izveido e-pasta instanci 
                            $email = Email::forge();
                            // Uzstāda "no kā" sūtīs e-pastu
                            $email->from('pilsetasudens@gmail.com', 'IS PILSETAS UDENS');
                            // Uzstāda "kam" sūtīs e-pastu
                            $email->to(Input::post('email'));
                            // Temats
                            $email->subject('Paroles atjaunošana');

                            // masīvs e-pasta ziņas datiem (kods, klienta numurs u.c.)
                            $email_data = array();
                            $email_data['code'] = $code;
                            $email_data['user_id'] = $user_data->id;

                            // Izveido html ķermeni no skata, ko sūtīt
                            $email->html_body(\View::forge('emails/forgot', $email_data));
                            
                            if($email->send())
                            {
                                Session::set_flash('success', 'Uz tavu e-pastu tika nosūtīta ziņa. Seko norādēm un atjaunosi savu paroli!');
                                Response::redirect('/abonents/aizmirsta-parole');
                            }
                            else 
                            {
                                Session::set_flash('error', 'Neveiksme! Neizdevās nosūtīt e-pastu.');
                                Response::redirect('/abonents/aizmirsta-parole');
                            }
                        }// 
                     }
                     // Nav atrasts lietotājs
                     else 
                     {
                        Session::set_flash('error', 'Neveiksme! Šāds lietotājs sistēmā nepastāv.');
                        Response::redirect('/abonents/aizmirsta-parole');
                     }
                }
                else
                {
                    Session::set_flash('error', 'Netika ievadīts korekts e-pasts!');
                    Response::redirect('/abonents/aizmirsta-parole'); 
                }
            }
                $this->template->title= "Paroles atjaunošana - Pilsētas ūdens";
                $this->template->content = View::forge('connection/forgot');
        }
        
        /**
	 * Funkcija: 3.3.1.3.	Lietotāja reģistrācijas apstiprināšanas e-pasta izsūtīšana (viesis)
         * Identifikators: IS_USER_RESEND
	 *
	 * Nosūta lietotājam atkārtotu reģistrācijas apstiprināšanas e-pastu
         * 
	 */
        public function action_resend($user_id = null)
        {
            // Ja lietotājs jau ir autorizējies un mēģina vēlreiz autorizēties, 
            // tad vedam viņu uz sistēmas sākumu
            if(Auth::check()) {
                Response::redirect('/');
            }
            
            if(!empty($user_id))
            {
                $user = Model_User::find($user_id);
                
                if($user && $user->is_confirmed == 'N')
                {
                    //Sagatavo lietotāja unikālo kodu
                    $code = substr(md5(uniqid(mt_rand(), true)) , 0, 8);
                    
                    $user->unique_code = $code;
                    // atjaunojam izveidošanas laiku, lai atkal ir 48h, lai varētu apstiprināt reģistrāciju
                    $user->created_at = Date::forge()->get_timestamp();
                    
                    // Izveido e-pasta instanci 
                    $email = Email::forge();
                    // Uzstāda "no kā" sūtīs e-pastu
                    $email->from('pilsetasudens@gmail.com', 'IS PILSETAS UDENS');
                    // Uzstāda "kam" sūtīs e-pastu
                    $email->to($user->email);
                    // Temats
                    $email->subject('Reģistrācijas apstiprināšana');

                    // masīvs e-pasta ziņas datiem (kods, klienta numurs u.c.)
                    $email_data = array();
                    $email_data['code'] = $code;

                    // Izveido html ķermeni no skata, ko sūtīt
                    $email->html_body(\View::forge('emails/regconfirm', $email_data));
                    
                    try
                    {
                        if($email->send() && $user->save())
                        {
                          Session::set_flash('success','E-pasts par reģistrācijas apstiprināšanu atkārtoti nosūtīts uz reģistrēto e-pastu. Lūdzu, seko norādēm e-pasta ziņā!');
                          Response::redirect('/apstiprinat/post');
                        }
                    } 
                      catch(\EmailSendingFailedException $e)
                      {
                            Session::set_flash('error','Notikusi sistēmas iekšēja kļūda! Nav iespējams nosūtīt e-pastu!');
                            Response::redirect('/apstiprinat/post');
                      }
                      // Netika ievadīts pareizs e-pasts
                      catch(\EmailValidationFailedException $e)
                      {
                            Session::set_flash('error','E-pasts netika nosūtīts! Ievadīts nekorekts e-pasts.');
                            Response::redirect('/apstiprinat/post');
                      }
                      // Cita kļūda
                      catch(Exception $exc)
                      {
                            Session::set_flash('error',"Notikusi sistēmas iekšēja kļūda! Lūdzu, ziņojiet par šo kļūdu administrācijai.");
                            Response::redirect('/apstiprinat/post');
                      }
                }
                else
                {
                    Session::set_flash('error','Lietotājs neeksistē!');
                    Response::redirect('/apstiprinat/post');
                }
            }
        }
        
        /**
	 * Funkcija: 3.3.1.8.	Lietotāja paroles izsūtīšana (klients)
         * Identifikators: IS_USER_RESET_PASS
	 *
	 * Nosūta lietotājam uz e-pastu jauno paroli
         * 
	 */
        public function action_change($code = null)
        {
            // datu masīvs, ko padod skatam
            $view_data = array();
            
            if(Input::method()=='GET')
            {
                // Pārbauda, vai ir pagaidu lietotājs, kuram ir ievadītais kods (code)
                $temp_user = Model_User::find_by('unique_code',$code);
                // Paņem no objekta lietotāja id
                foreach ($temp_user as $data) {
                    $user_id = $data->id;
                }
                // Ja ir atrasts lietotāja id, tad atrod lietotāja objektu
                if(!empty($user_id)) $user_data = Model_User::find($user_id);
                else $user_data = false;
                
                if($user_data)
                {
                    if($user_data->unique_code == $code)
                    {
                        //Sagatavo lietotāja unikālo kodu
                        $code = substr(md5(uniqid(mt_rand(), true)) , 0, 8);
                        // mainam lietotāja unikālo kodu
                        $user_data -> unique_code = $code;
                        // jaunā parole, kas jānosūta klientam
                        $new = Auth::reset_password($user_data -> username);
                        
                        // Izveido e-pasta instanci 
                        $email = Email::forge();
                        // Uzstāda "no kā" sūtīs e-pastu
                        $email->from('pilsetasudens@gmail.com', 'IS PILSETAS UDENS');
                        // Uzstāda "kam" sūtīs e-pastu
                        $email->to($user_data->email);
                        // Temats
                        $email->subject('Mainīta parole');

                        // masīvs e-pasta ziņas datiem (kods, klienta numurs u.c.)
                        $email_data = array();
                        $email_data['code'] = $new;

                        // Izveido html ķermeni no skata, ko sūtīt
                        $email->html_body(\View::forge('emails/change', $email_data));
                        
                        if($email -> send())
                        {
                            Session::set_flash('success', 'Parole nosūtīta jums uz e-pastu!');
                            Response::redirect('/abonents/aizmirsta-parole');
                        }
                        else
                        {
                            Session::set_flash('error', 'Sistēmas kļūda! Paroli nebija iespējams nosūtīt uz e-pastu, taču tā tika nomainīta. Lūdzam sazināties ar administrāciju!');
                            Response::redirect('/abonents/aizmirsta-parole');
                        }
                        
                    }
                }
                else
                {
                    Session::set_flash('error', 'Neveiksme! Šāds lietotājs sistēmā nepastāv.');
                    Response::redirect('/abonents/aizmirsta-parole');
                }
            }

            $this->template->title = "Paroles maiņa - Pilsētas ūdens";
            $this->template->content = View::forge('connection/change');
        }
}
