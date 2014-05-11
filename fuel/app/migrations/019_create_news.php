<?php

namespace Fuel\Migrations;

class Create_news
{
	public function up()
	{
		\DBUtil::create_table('news', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'title' => array('constraint' => 50, 'type' => 'varchar'),
			'body' => array('type' => 'text'),
			'excerpt' => array('type' => 'text'),
			'status' => array('constraint' => 40, 'type' => 'varchar'),
			'lang' => array('constraint' => 2, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('news');
	}
}