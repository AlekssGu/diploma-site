<?php
/*
 *  Ceļu definēšana
 */
return array(
	'_root_'  => 'main/index',                      // Sākumlapas ceļš
	'_404_'   => 'main/404',                        // 404 kļūdas ceļš
    
        // Lietotāja pieslēgšanās/paroles atjaunošanas lapas:
        'abonents/registreties' => 'connection/register',       // Reģistrācijas lapa
        'apstiprinat/:code' => 'connection/confirm/$1',     // Reģistrācijas apstiprināšanas lapa (GET)
        'apstiprinat/post' => 'connection/confirm',         // Reģistrācijas apstiprināšanas lapa (POST)
        'abonents/pieslegties' => 'connection/login',             // Autorizācijas lapa
        'abonents/atslegties' => 'connection/logout',           // Iziet no sistēmas
        'abonents/aizmirsta-parole' => 'connection/forgot',           // Aizmirsta parole
        'abonents/izsutit/:id' => 'connection/resend/$1',    // Paroles atjaunošana
        'abonents/mainit/:code' => 'connection/change/$1',  // Paroles maiņa (GET)
        'abonents/mainit-datus' => 'connection/change_data', // Lietotājs maina paroli
        //--

        // navigācijas lapas (ar statisko saturu):
        'aktuali' => 'static/recent',
        'aktuali/karte' => 'static/recent_map',
        'aktuali/jaunumi' => 'static/recent_news',
        'pakalpojumi' => 'static/services',

        // Par mums sadaļas:
        'par-uznemumu/:page_name' => 'static/about/$1',
        //--
    
    
        // Palīdzības sadaļas:
        'palidziba' => 'static/help',
        'palidziba/buj' => 'static/help_faq',
        'palidziba/sazinaties' => 'static/help_contact',
        // --
    
        // Klientam pieejamās sadaļas:
        'klients' => 'client/client',
        'klients/pievienot-objektu' => 'client/add_object', 
        'klients/pievienot-skaititaju' => 'client/add_meter',
        'abonents/iesniegt-merijumu' => 'client/add_reading',
        'klients/objekti' => 'client/all_objects',
        'klients/objekti/dzest/:id' => 'client/all_objects_delete/$1',
        'klients/objekti/apskatit/:id' => 'client/all_objects_show/$1',
        'abonents/objekti/radijumi/:id' => 'client/readings_history/$1',
        'abonents/pakalpojumi/apskatit/:object_id/:service_id' => 'client/show_service',
        'abonents/pakalpojumi/pasutit' => 'client/request_service',
    
        'darbinieks' => 'worker/worker',
        'darbinieks/abonenti' => 'worker/clients',
        'darbinieks/abonenti/labot-datus' => 'worker/change_client_data',
        'darbinieks/abonenti/labot-pakalpojumu' => 'worker/modify_service',
        'darbinieks/abonenti/pievienot-pakalpojumu' => 'worker/add_service',
        'darbinieks/abonenti/atslegt-pakalpojumu' => 'worker/deactivate_service',
        'darbinieks/abonenti/apskatit-pakalpojumu/:object_id/:service_id' => 'client/show_service',
        'darbinieks/abonenti/skaititaji/nonemt/:service_id' => 'worker/remove_meter/$1',
        'darbinieks/iesniegtie/dati' => 'worker/all_entered_data',
        'darbinieks/abonenti/dzest-objektu/:object_id' => 'worker/delete_object/$1',
        'darbinieks/abonenti/skaititaji/labot' => 'worker/edit_meter',
    
        //Sadaļa: Skaitītāju dati (darbinieks)
        'darbinieks/skaititaji/radijumi/atgriezt' => 'worker/return_reading',
        'darbinieks/skaititaji/radijumi/apstiprinat/:reading_id/:client_id' => 'worker/accept_reading',
    
        //Sadaļa: Pieejamie pakalpojumi (darbinieks)
        'darbinieks/pakalpojumi' => 'worker/services',
        'darbinieks/pakalpojumi/pievienot' => 'worker/create_service',
        'darbinieks/pakalpojumi/dzest/:service_id' => 'worker/delete_service/$1',
    
        //ajax izsaukumiem
        'ieladet-datus/:id' => 'worker/load_client/$1',
        'ieladet-objekta-datus/:id' => 'worker/load_object_data/$1',
);