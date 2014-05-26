<?php

class Model_Object extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'client_id', 
		'address_id', 
		'name',
		'notes',
                'is_deleted',
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
	protected static $_table_name = 'objects';

        protected static $_has_one = array(
            'user' => array(
                'key_from' => 'client_id',
                'model_to' => 'Model_User',
                'key_to' => 'id',
                'cascade_save' => false,
                'cascade_delete' => true,
            ),
            'address' => array(
                'key_from' => 'address_id',
                'model_to' => 'Model_Address',
                'key_to' => 'id',
                'cascade_save' => false,
                'cascade_delete' => true,
            ),
        );        
}
