<?php

namespace Fuel\Migrations;

class Create_questions
{
	public function up()
	{
		\DBUtil::create_table('questions', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'fullname' => array('constraint' => 120, 'type' => 'varchar', 'null' => true),
			'email' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'topic_id' => array('constraint' => 11, 'type' => 'int'),
			'message' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('questions');
	}
}