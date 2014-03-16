<?php

namespace Fuel\Migrations;

class Create_users
{
	public function up()
	{
		\DBUtil::create_table('users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'address_id' => array('constraint' => 11, 'type' => 'int'), // Faktiskā adrese
                        'secondary_addr_id' => array('constraint' => 11, 'type' => 'int'), // Juridiskā/Deklarētā adrese
			'username' => array('constraint' => 12, 'type' => 'varchar'),
			'password' => array('constraint' => 255, 'type' => 'varchar'),
			'email' => array('constraint' => 250, 'type' => 'varchar'),
                    	'profile_fields' => array('type' => 'text'),
			'group' => array('constraint' => 11, 'type' => 'int'),
			'mobile_phone' => array('constraint' => 45, 'type' => 'varchar', 'null' => true),
			'is_active' => array('constraint' => 1, 'type' => 'varchar', 'default' => 'N'),
			'is_confirmed' => array('constraint' => 1, 'type' => 'varchar', 'default' => 'N'),
			'is_messages' => array('constraint' => 1, 'type' => 'varchar', 'default' => 'N'),
                        'person_type' => array('constraint' => 1, 'type' => 'varchar'), // P = person, C = company
			'unique_code' => array('constraint' => 255, 'type' => 'varchar'),
                        'last_login' => array('constraint' => 20, 'type' => 'int', 'null' => true),
			'login_hash' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}