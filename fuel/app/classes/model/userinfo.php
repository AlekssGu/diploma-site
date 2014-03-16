<?php

class Model_Userinfo extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'address_id',
		'secondary_addr_id',
		'name',
		'surname',
		'person_code',
		'client_number',
		'mobile_phone',
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
	protected static $_table_name = 'userinfos';

}
