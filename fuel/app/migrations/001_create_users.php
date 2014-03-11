<?php

namespace Fuel\Migrations;

class Create_users
{
	public function up()
	{
		\DBUtil::create_table('users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'username' => array('constraint' => 12, 'type' => 'varchar'), // klienta numurs
			'email' => array('constraint' => 255, 'type' => 'varchar'),
			'password' => array('constraint' => 255, 'type' => 'varchar'),
			'group' => array('constraint' => 11, 'type' => 'int'),
			'name' => array('constraint' => 45, 'type' => 'varchar'),
			'surname' => array('constraint' => 45, 'type' => 'varchar'),
			'city_id' => array('constraint' => 11, 'type' => 'int'),
			'street' => array('constraint' => 45, 'type' => 'varchar'),
			'house' => array('constraint' => 45, 'type' => 'varchar'),
			'flat' => array('constraint' => 11, 'type' => 'int'),
			'district' => array('constraint' => 45, 'type' => 'varchar'),
			'post_code' => array('constraint' => 7, 'type' => 'varchar'),
			'mobile_phone' => array('constraint' => 45, 'type' => 'varchar'),
			'is_active' => array('constraint' => 1, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}