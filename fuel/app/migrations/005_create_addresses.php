<?php

namespace Fuel\Migrations;

class Create_addresses
{
	public function up()
	{
		\DBUtil::create_table('addresses', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'client_id' => array('constraint' => 11, 'type' => 'int'),
			'city_id' => array('constraint' => 11, 'type' => 'int'),
			'street' => array('constraint' => 45, 'type' => 'varchar'),
			'house' => array('constraint' => 45, 'type' => 'varchar'),
			'flat' => array('constraint' => 11, 'type' => 'int'),
			'district' => array('constraint' => 45, 'type' => 'varchar'),
			'post_code' => array('constraint' => 7, 'type' => 'varchar'),
			'addr_type' => array('constraint' => 1, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('addresses');
	}
}