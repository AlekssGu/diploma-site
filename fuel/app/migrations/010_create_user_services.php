<?php

namespace Fuel\Migrations;

class Create_user_services
{
	public function up()
	{
		\DBUtil::create_table('user_services', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'obj_id' => array('constraint' => 11, 'type' => 'int'),
			'srv_id' => array('constraint' => 11, 'type' => 'int'),
			'date_from' => array('type' => 'date'),
			'date_to' => array('type' => 'date'),
			'notes' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
                        'is_active' => array('constraint' => 1, 'type' => 'varchar', 'default' => 'N'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('user_services');
	}
}