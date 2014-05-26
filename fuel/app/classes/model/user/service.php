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
	protected static $_table_name = 'user_services';

        protected static $_has_one = array(
            'service' => array(
                'key_from' => 'srv_id',
                'model_to' => 'Model_Service',
                'key_to' => 'id',
                'cascade_save' => false,
                'cascade_delete' => true,
            ),
            'object' => array(
                'key_from' => 'obj_id',
                'model_to' => 'Model_Object',
                'key_to' => 'id',
                'cascade_save' => false,
                'cascade_delete' => false,
            ),
        );
        
}
