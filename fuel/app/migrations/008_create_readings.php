<?php

namespace Fuel\Migrations;

class Create_readings
{
	public function up()
	{
		\DBUtil::create_table('readings', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'meter_id' => array('constraint' => 11, 'type' => 'int'),
			'lead' => array('constraint' => 50, 'type' => 'varchar'),
			'date_taken' => array('type' => 'date'),
			'period' => array('constraint' => 50, 'type' => 'varchar'),
                        'status' => array('constraint' => 50, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('readings');
	}
}