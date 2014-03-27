<?php

namespace Fuel\Migrations;

class Create_objects
{
	public function up()
	{
		\DBUtil::create_table('objects', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
                        'client_id' => array('constraint' => 11, 'type' => 'int'),
                        'address_id' => array('constraint' => 11, 'type' => 'int'),
                        'name' => array('constraint' => 100, 'type' => 'varchar'),
			'notes' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('objects');
	}
}