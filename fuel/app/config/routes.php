<?php
/*
 *  Ceļu definēšana
 */
return array(
	'_root_'  => 'main/index',                      // Sākumlapas ceļš
	'_404_'   => 'main/404',                        // 404 kļūdas ceļš
        'redmine' => 'main/redmine',
    
        // Lietotāja pieslēgšanās/paroles atjaunošanas lapas:
        'klients/registreties' => 'connection/register',       // Reģistrācijas lapa
        'apstiprinat/:code' => 'connection/confirm/$1',     // Reģistrācijas apstiprināšanas lapa (GET)
        'apstiprinat/post' => 'connection/confirm',         // Reģistrācijas apstiprināšanas lapa (POST)
        'klients/pieslegties' => 'connection/login',             // Autorizācijas lapa
        'klients/atslegties' => 'connection/logout',           // Iziet no sistēmas
        'klients/aizmirsta-parole' => 'connection/forgot',           // Aizmirsta parole
        'klients/izsutit/:id' => 'connection/resend/$1',    // Paroles atjaunošana
        'klients/mainit/:code' => 'connection/change/$1',  // Paroles maiņa (GET)
        //--

        // navigācijas lapas (ar statisko saturu):
        'aktuali' => 'static/recent', //Pāradresē uz root
        'aktuali/jaunumi' => 'static/recent_news', //Jaunumu lapa
        'aktuali/darbi' => 'static/all_issues', //Visi bojājumi un darbi
        'pakalpojumi' => 'static/services', //Pakalpojumu lapa

        // Par mums sadaļas:
        'par-uznemumu/:page_name' => 'static/about/$1', //Par mums sadaļas
        //--

        // Palīdzības sadaļas:
            'palidziba' => 'static/help', //Pāradresē uz root
            'palidziba/buj' => 'static/help_faq', //Biežāk uzdotie jautājumi
            'palidziba/sazinaties' => 'static/help_contact', //Sazināties ar uzņēmumu
        // --
    
        // Visiem pieejamās sadaļas:
            'pazinot-par-bojajumu' => 'main/report_issue',
    
    
        // Klientam pieejamās sadaļas:
            'klients' => 'client/client',
            'klients/mainit-datus' => 'client/change_data', // Lietotājs maina paroli,telefona numuru vai e-pastu
            'klients/iesniegt-merijumu' => 'client/add_reading',
            'klients/objekti' => 'client/all_objects',
            'klients/objekti/apskatit/:id' => 'client/all_objects_show/$1',
            'klients/objekti/radijumi/:id' => 'client/readings_history/$1',
            'klients/pakalpojumi/apskatit/:object_id/:service_id' => 'client/show_service',
            'klients/pakalpojumi/pasutit' => 'client/request_service',
            'klients/pakalpojumi/atteikties' => 'client/dismiss_service',
            'klients/ievadit-merijumus' => 'client/all_readings',
    
        // Darbinieka sadaļas: 
            'darbinieks' => 'worker/worker',
            'darbinieks/klienti' => 'worker/clients',
            'darbinieks/bojajumu-pazinojumi/dzest/:issue_id' => 'worker/delete_issue/$1',    

            'darbinieks/klienti/labot-datus' => 'worker/change_client_data',

            //Sadaļa: Pakalpojumi
            'darbinieks/klienti/labot-pakalpojumu' => 'worker/modify_service',
            'darbinieks/klienti/pievienot-pakalpojumu' => 'worker/add_service',
            'darbinieks/klienti/atslegt-pakalpojumu' => 'worker/deactivate_service',
            'darbinieks/klienti/apskatit-pakalpojumu/:object_id/:service_id' => 'client/show_service',

            //Sadaļa: Skaitītāji
            'darbinieks/klienti/skaititaji/pievienot' => 'worker/add_meter',
            'darbinieks/klienti/skaititaji/nonemt/:service_id' => 'worker/remove_meter/$1',

            'darbinieks/iesniegtie/dati' => 'worker/all_entered_data',
            'darbinieks/iesniegtie-jautajumi/dzest/:quest_id' => 'worker/delete_question/$1',
    
            'darbinieks/klienti/dzest-objektu/:object_id' => 'worker/delete_object/$1',
            'darbinieks/klienti/skaititaji/labot' => 'worker/edit_meter',

            //Sadaļa: Klienta objekti
            'darbinieks/klienti/pievienot-objektu' => 'worker/add_object', 

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
    
            //Sadaļa: Jaunumu pārvaldība
            'darbinieks/jaunumi' => 'worker/news',
            'darbinieks/jaunumi/izveidot' => 'worker/create_news',
            'darbinieks/jaunumi/skatit/:news_id' => 'worker/view_news/$1',
            'darbinieks/jaunumi/labot/:news_id' => 'worker/edit_news/$1',
            'darbinieks/jaunumi/dzest/:news_id' => 'worker/delete_news/$1',
    
            //ajax izsaukumiem
            'ieladet-datus/:id' => 'worker/load_client/$1',
            'ieladet-objekta-datus/:id' => 'worker/load_object_data/$1',
);