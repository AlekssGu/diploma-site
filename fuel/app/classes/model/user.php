<?php

class Model_User extends \Orm\Model
{
	protected static $_properties = array(
                'id',
                'username', // klienta numurs
                'password',
                'email',
                'profile_fields',
                'group',
                'last_login',
                'login_hash',
                'created_at',
                'updated_at'
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

}
