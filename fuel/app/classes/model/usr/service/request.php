<?php

class Model_Usr_Service_Request extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'client_id',
		'object_id',
		'service_id',
                'usr_srv_id',
		'date_from',
		'date_to',
		'notes',
                'status',
                'status_notes',
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
	protected static $_table_name = 'usr_service_requests';

}
