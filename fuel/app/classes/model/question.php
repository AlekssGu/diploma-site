<?php

class Model_Question extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'user_id',
		'fullname',
		'email',
		'topic_id',
		'message',
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
	protected static $_table_name = 'questions';

        protected static $_has_one = array(
            'topic' => array(
                'key_from' => 'topic_id',
                'model_to' => 'Model_Topic',
                'key_to' => 'id',
                'cascade_save' => false,
                'cascade_delete' => false,
            ),
        );
}
