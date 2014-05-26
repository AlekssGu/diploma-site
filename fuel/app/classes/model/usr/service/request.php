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

        protected static $_has_one = array(
            'user' => array(
                'key_from' => 'client_id',
                'model_to' => 'Model_User',
                'key_to' => 'id',
                'cascade_save' => false,
                'cascade_delete' => true,
            ),
            'object' => array(
                'key_from' => 'object_id',
                'model_to' => 'Model_Object',
                'key_to' => 'id',
                'cascade_save' => false,
                'cascade_delete' => false,
            ),
        );
}
