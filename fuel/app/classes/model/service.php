<?php

class Model_Service extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'code_id',
		'name',
		'description',
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
	protected static $_table_name = 'services';
        
        protected static $_belongs_to = array(
            'user_service' => array(
                'key_from' => 'id',
                'model_to' => 'Model_User_Service',
                'key_to' => 'srv_id',
                'cascade_save' => false,
                'cascade_delete' => false,
            ),
        );

}
