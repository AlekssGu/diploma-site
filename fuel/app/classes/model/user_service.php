<?php

class Model_User_Service extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'obj_id',
		'srv_id',
		'date_from',
		'date_to',
		'notes',
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
	protected static $_table_name = 'user_services';

}
