<?php

namespace Fuel\Migrations;

class Create_persons
{
	public function up()
	{
		\DBUtil::create_table('persons', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'address_id' => array('constraint' => 11, 'type' => 'int'),
			'secondary_addr_id' => array('constraint' => 11, 'type' => 'int'),
			'name' => array('constraint' => 45, 'type' => 'varchar'),
			'surname' => array('constraint' => 45, 'type' => 'varchar'),
			'person_code' => array('constraint' => 12, 'type' => 'varchar'),
			'client_number' => array('constraint' => 8, 'type' => 'varchar'),
			'mobile_phone' => array('constraint' => 45, 'type' => 'varchar'),
                        'person_type' => array('constraint' => 1, 'type' => 'varchar'), // P = person, C = company
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('persons');
	}
}