<?php

namespace Fuel\Migrations;

class Add_status_notes_to_usr_service_requests
{
	public function up()
	{
		\DBUtil::add_fields('usr_service_requests', array(
			'status_notes' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('usr_service_requests', array(
			'status_notes'

		));
	}
}