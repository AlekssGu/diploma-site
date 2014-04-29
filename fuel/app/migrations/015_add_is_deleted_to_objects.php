<?php

namespace Fuel\Migrations;

class Add_is_deleted_to_objects
{
	public function up()
	{
		\DBUtil::add_fields('objects', array(
			'is_deleted' => array('constraint' => 1, 'type' => 'varchar', 'default' => 'N'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('objects', array(
			'is_deleted'

		));
	}
}