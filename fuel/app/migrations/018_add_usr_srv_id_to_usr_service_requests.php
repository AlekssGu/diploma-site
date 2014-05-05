<?php

namespace Fuel\Migrations;

class Add_usr_srv_id_to_usr_service_requests
{
	public function up()
	{
		\DBUtil::add_fields('usr_service_requests', array(
			'usr_srv_id' => array('constraint' => 11, 'type' => 'int'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('usr_service_requests', array(
			'usr_srv_id'

		));
	}
}