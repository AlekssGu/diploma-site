<?php

namespace Fuel\Migrations;

class Create_external_users
{
	public function up()
	{
		\DBUtil::create_table('external_users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'surname' => array('constraint' => 255, 'type' => 'varchar'),
			'person_code' => array('constraint' => 255, 'type' => 'varchar'),
			'person_type' => array('constraint' => 255, 'type' => 'varchar'),
			'pri_street' => array('constraint' => 255, 'type' => 'varchar'),
			'pri_house' => array('constraint' => 255, 'type' => 'varchar'),
			'pri_flat' => array('constraint' => 255, 'type' => 'varchar'),
			'pri_district' => array('constraint' => 255, 'type' => 'varchar'),
			'pri_postcode' => array('constraint' => 255, 'type' => 'varchar'),
			'pri_city' => array('constraint' => 255, 'type' => 'varchar'),
			'sec_street' => array('constraint' => 255, 'type' => 'varchar'),
			'sec_house' => array('constraint' => 255, 'type' => 'varchar'),
			'sec_flat' => array('constraint' => 255, 'type' => 'varchar'),
			'sec_district' => array('constraint' => 255, 'type' => 'varchar'),
			'sec_postcode' => array('constraint' => 255, 'type' => 'varchar'),
			'sec_city' => array('constraint' => 255, 'type' => 'varchar'),
			'client_number' => array('constraint' => 255, 'type' => 'varchar'),
			'mobile_phone' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('external_users');
	}
}