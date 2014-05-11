<?php

namespace Fuel\Migrations;

class Create_emergencies
{
	public function up()
	{
		\DBUtil::create_table('emergencies', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'lat' => array('constraint' => '10,6', 'type' => 'float'),
			'lon' => array('constraint' => '10,6', 'type' => 'float'),
			'notes' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('emergencies');
	}
}