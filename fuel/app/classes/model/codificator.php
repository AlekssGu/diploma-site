<?php

class Model_Codificator extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'code',
		'used_in',
		'comments',
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
	protected static $_table_name = 'codificators';
        
        protected static $_belongs_to = array(
            'service' => array(
                'key_from' => 'id',
                'model_to' => 'Model_Service',
                'key_to' => 'code_id',
                'cascade_save' => false,
                'cascade_delete' => false,
            ),
        );        

}
