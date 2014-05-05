<?php

namespace Fuel\Migrations;

class Add_status_to_usr_service_requests
{
	public function up()
	{
		\DBUtil::add_fields('usr_service_requests', array(
			'status' => array('constraint' => 40, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('usr_service_requests', array(
			'status'

		));
	}
}