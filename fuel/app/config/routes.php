<?php
/*
 *  Ceļu definēšana
 */
return array(
	'_root_'  => 'main/index',                      // Sākumlapas ceļš
	'_404_'   => 'main/404',                        // 404 kļūdas ceļš
    
        // Lietotāja pieslēgšanās/paroles atjaunošanas lapas:
        'user/register' => 'connection/register',       // Reģistrācijas lapa
        'confirm/:code' => 'connection/confirm/$1',     // Reģistrācijas apstiprināšanas lapa (GET)
        'confirm/post' => 'connection/confirm',         // Reģistrācijas apstiprināšanas lapa (POST)
        'user/login' => 'connection/login',             // Autorizācijas lapa
        'user/logout' => 'connection/logout',           // Iziet no sistēmas
        'user/forgot' => 'connection/forgot',           // Aizmirsta parole
        'user/resend/:id' => 'connection/resend/$1',    // Paroles atjaunošana
        'user/change/:code' => 'connection/change/$1',  // Paroles maiņa (GET)
        //--

        // navigācijas lapas (ar statisko saturu):
        'jaunumi' => 'static/news',
        'pakalpojumi' => 'static/services',

        // Par mums sadaļas:
        'par-uznemumu' => 'static/about', // pāradresē uz sākumu
        'par-uznemumu/darbiba' => 'static/about_activity',
        'par-uznemumu/dokumenti' => 'static/about_docs',
        'par-uznemumu/parvalde' => 'static/about_board',
        'par-uznemumu/projekti' => 'static/about_projects',
        'par-uznemumu/vesture' => 'static/about_history'
        //--
);