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
        
        protected static $_has_one = array(
            'user' => array(
                'key_from' => 'client_id',
                'model_to' => 'Model_User',
                'key_to' => 'id',
                'cascade_save' => false,
                'cascade_delete' => false,
            ),
            'city' => array(
                'key_from' => 'city_id',
                'model_to' => 'Model_City',
                'key_to' => 'city_id',
                'cascade_save' => false,
                'cascade_delete' => false,
            ),
        );

}
