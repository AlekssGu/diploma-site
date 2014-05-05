<?php
/*
 *  Ceļu definēšana
 */
return array(
	'_root_'  => 'main/index',                      // Sākumlapas ceļš
	'_404_'   => 'main/404',                        // 404 kļūdas ceļš
        'redmine' => 'main/redmine',
    
        // Lietotāja pieslēgšanās/paroles atjaunošanas lapas:
        'abonents/registreties' => 'connection/register',       // Reģistrācijas lapa
        'apstiprinat/:code' => 'connection/confirm/$1',     // Reģistrācijas apstiprināšanas lapa (GET)
        'apstiprinat/post' => 'connection/confirm',         // Reģistrācijas apstiprināšanas lapa (POST)
        'abonents/pieslegties' => 'connection/login',             // Autorizācijas lapa
        'abonents/atslegties' => 'connection/logout',           // Iziet no sistēmas
        'abonents/aizmirsta-parole' => 'connection/forgot',           // Aizmirsta parole
        'abonents/izsutit/:id' => 'connection/resend/$1',    // Paroles atjaunošana
        'abonents/mainit/:code' => 'connection/change/$1',  // Paroles maiņa (GET)
        //--

        // navigācijas lapas (ar statisko saturu):
        'aktuali' => 'static/recent', //Pāradresē uz root
        'aktuali/jaunumi' => 'static/recent_news', //Jaunumu lapa
        'pakalpojumi' => 'static/services', //Pakalpojumu lapa

        // Par mums sadaļas:
        'par-uznemumu/:page_name' => 'static/about/$1', //Par mums sadaļas
        //--

        // Palīdzības sadaļas:
            'palidziba' => 'static/help', //Pāradresē uz root
            'palidziba/buj' => 'static/help_faq', //Biežāk uzdotie jautājumi
            'palidziba/sazinaties' => 'static/help_contact', //Sazināties ar uzņēmumu
        // --
    
        // Klientam pieejamās sadaļas:
            'abonents' => 'client/client',
            'abonents/mainit-datus' => 'client/change_data', // Lietotājs maina paroli,telefona numuru vai e-pastu
            'abonents/iesniegt-merijumu' => 'client/add_reading',
            'abonents/objekti' => 'client/all_objects',
            'abonents/objekti/apskatit/:id' => 'client/all_objects_show/$1',
            'abonents/objekti/radijumi/:id' => 'client/readings_history/$1',
            'abonents/pakalpojumi/apskatit/:object_id/:service_id' => 'client/show_service',
            'abonents/pakalpojumi/pasutit' => 'client/request_service',
            'abonents/pakalpojumi/atteikties' => 'client/dismiss_service',
            'abonents/ievadit-merijumus' => 'client/all_readings',
            'abonents/pazinot-par-bojajumu' => 'client/report_issue',
    
        // Darbinieka sadaļas: 
            'darbinieks' => 'worker/worker',
            'darbinieks/abonenti' => 'worker/clients',

            'darbinieks/abonenti/labot-datus' => 'worker/change_client_data',

            //Sadaļa: Pakalpojumi
            'darbinieks/abonenti/labot-pakalpojumu' => 'worker/modify_service',
            'darbinieks/abonenti/pievienot-pakalpojumu' => 'worker/add_service',
            'darbinieks/abonenti/atslegt-pakalpojumu' => 'worker/deactivate_service',
            'darbinieks/abonenti/apskatit-pakalpojumu/:object_id/:service_id' => 'client/show_service',

            //Sadaļa: Skaitītāji
            'darbinieks/abonenti/skaititaji/pievienot' => 'worker/add_meter',
            'darbinieks/abonenti/skaititaji/nonemt/:service_id' => 'worker/remove_meter/$1',

            'darbinieks/iesniegtie/dati' => 'worker/all_entered_data',
            'darbinieks/abonenti/dzest-objektu/:object_id' => 'worker/delete_object/$1',
            'darbinieks/abonenti/skaititaji/labot' => 'worker/edit_meter',

            //Sadaļa: Klienta objekti
            'darbinieks/abonenti/pievienot-objektu' => 'worker/add_object', 

            //Sadaļa: Skaitītāju dati (darbinieks)
            'darbinieks/skaititaji/radijumi/atgriezt' => 'worker/return_reading',
            'darbinieks/skaititaji/radijumi/apstiprinat/:reading_id/:client_id' => 'worker/accept_reading',
            'darbinieks/pakalpojumi/pieprasijumi/atteikt' => 'worker/reject_service_request',
            'darbinieks/pakalpojumi/pieprasijumi/apstiprinat/:req_id' => 'worker/accept_service_request/$1',

            //Sadaļa: Pieejamie pakalpojumi (darbinieks)
            'darbinieks/pakalpojumi' => 'worker/services',
            'darbinieks/pakalpojumi/pievienot' => 'worker/create_service',
            'darbinieks/pakalpojumi/dzest/:service_id' => 'worker/delete_service/$1',
            'darbinieks/pakalpojumi/labot' => 'worker/edit_service',
    
            //ajax izsaukumiem
            'ieladet-datus/:id' => 'worker/load_client/$1',
            'ieladet-objekta-datus/:id' => 'worker/load_object_data/$1',
);