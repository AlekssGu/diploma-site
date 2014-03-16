<?php

class Model_Address extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'client_id',
		'city_id',
		'street',
		'house',
		'flat',
		'district',
		'post_code',
		'addr_type',
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
	protected static $_table_name = 'addresses';

}
