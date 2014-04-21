<?php

class Model_Meter extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'service_id',
		'date_from',
		'date_to',
		'meter_type',
		'water_type',
		'worker_id',
		'meter_number',
		'meter_model',
		'meter_lead',
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
	protected static $_table_name = 'meters';

}
