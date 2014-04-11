<?php

namespace Fuel\Migrations;

class Create_services
{
	public function up()
	{
		\DBUtil::create_table('services', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'code' => array('constraint' => 40, 'type' => 'varchar'),
			'name' => array('constraint' => 100, 'type' => 'varchar'),
			'description' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('services');
	}
}