<?php

namespace Fuel\Migrations;

class Create_usr_service_requests
{
	public function up()
	{
		\DBUtil::create_table('usr_service_requests', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'client_id' => array('constraint' => 11, 'type' => 'int'),
			'object_id' => array('constraint' => 11, 'type' => 'int'),
			'service_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'date_from' => array('type' => 'date'),
			'date_to' => array('type' => 'date', 'null' => true),
			'notes' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('usr_service_requests');
	}
}