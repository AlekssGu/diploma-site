<?php

class Model_Person extends \Orm\Model
{       // Šo visu informāciju iegūst no esošas sistēmas
	protected static $_properties = array(
		'id',
		'address_id',
		'secondary_addr_id',
		'name',
		'surname',
		'person_code',
		'client_number',
		'mobile_phone',
                'person_type',
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
	protected static $_table_name = 'persons';

}
