<?php

class Model_User extends \Orm\Model
{       // Šo visu aizpilda pie reģistrācijas / reģistrācijas apstiprināšanas
	protected static $_properties = array(
		'id',
                'person_id',
		'username',
		'password',
		'email',
            	'profile_fields',
		'group',
		'is_active',
		'is_confirmed',
                'is_messages',
		'unique_code',
            	'last_login',
		'login_hash',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);
	protected static $_table_name = 'users';
        
        protected static $_has_one = array(
            'person' => array(
                'key_from' => 'person_id',
                'model_to' => 'Model_Person',
                'key_to' => 'id',
                'cascade_save' => false,
                'cascade_delete' => true,
            ),
        );

}
