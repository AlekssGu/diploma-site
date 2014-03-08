<?php

class Model_Isu_User extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'client_number',
		'email',
		'password',
		'group',
		'name',
		'surname',
		'city_id',
		'street',
		'house',
		'flat',
		'district',
		'post_code',
		'mobile_phone',
		'is_active',
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
	protected static $_table_name = 'isu_users';

}
