<?php

namespace Fuel\Migrations;

class Add_notes_to_readings
{
	public function up()
	{
		\DBUtil::add_fields('readings', array(
			'notes' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('readings', array(
			'notes'

		));
	}
}