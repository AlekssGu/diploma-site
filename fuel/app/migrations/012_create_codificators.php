<?php

namespace Fuel\Migrations;

class Create_codificators
{
	public function up()
	{
		\DBUtil::create_table('codificators', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'code' => array('constraint' => 40, 'type' => 'varchar'),
			'used_in' => array('constraint' => 100, 'type' => 'varchar'),
			'comments' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('codificators');
	}
}