<?php

namespace Fuel\Migrations;

class Create_client_histories
{
	public function up()
	{
		\DBUtil::create_table('client_histories', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'client_id' => array('constraint' => 11, 'type' => 'int'),
			'notes' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('client_histories');
	}
}