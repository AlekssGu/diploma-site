<?php

namespace Fuel\Migrations;

class Create_meters
{
	public function up()
	{
		\DBUtil::create_table('meters', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'object_id' => array('constraint' => 11, 'type' => 'int'),
			'date_from' => array('type' => 'date'),
			'date_to' => array('type' => 'date'),
			'meter_type' => array('constraint' => 1, 'type' => 'varchar'),
			'water_type' => array('constraint' => 1, 'type' => 'varchar'),
			'worker_id' => array('constraint' => 11, 'type' => 'int'),
			'meter_number' => array('constraint' => 20, 'type' => 'varchar'),
			'meter_model' => array('constraint' => 50, 'type' => 'varchar'),
			'meter_lead' => array('constraint' => 11, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('meters');
	}
}