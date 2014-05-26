<?php

class Model_Topic extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'question',
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
	protected static $_table_name = 'topics';
        
        protected static $_belongs_to = array(
            'question' => array(
                'key_from' => 'id',
                'model_to' => 'Model_Question',
                'key_to' => 'topic_id',
                'cascade_save' => false,
                'cascade_delete' => false,
            ),
        );

}
