<?php

class Model_Reading extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'meter_id',
		'lead',
		'date_taken',
		'period',
                'status',
		'created_at',
		'updated_at',
                'notes'
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
	protected static $_table_name = 'readings';

        protected static $_has_one = array(
            'meter' => array(
                'key_from' => 'meter_id',
                'model_to' => 'Model_Meter',
                'key_to' => 'id',
                'cascade_save' => false,
                'cascade_delete' => false,
            ),
        );        
}
