<?php
use Orm\Model;

class Model_News extends Model
{
	protected static $_properties = array(
		'id',
		'title',
		'body',
		'excerpt',
		'status',
		'lang',
                'filename',
                'filename_sys',
                'file_source',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('title', 'Title', 'required|max_length[50]');
		$val->add_field('body', 'Body', 'required');
		$val->add_field('excerpt', 'Excerpt', 'required');

		return $val;
	}

}
