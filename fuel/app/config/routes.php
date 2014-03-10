<?php
/*
 *  Ceļu definēšana
 */
return array(
	'_root_'  => 'main/index',                      // Sākumlapas ceļš
	'_404_'   => 'main/404',                        // 404 kļūdas ceļš
    
        'user/register' => 'connection/register',       // Reģistrācijas lapa
        'confirm/:code' => 'connection/confirm/$1',     // Reģistrācijas apstiprināšanas lapa (GET)
        'confirm/post' => 'connection/confirm',         // Reģistrācijas apstiprināšanas lapa (POST)
        
        'user/login' => 'connection/login',             // Autorizācijas lapa
);