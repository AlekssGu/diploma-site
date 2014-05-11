<?php

namespace Fuel\Migrations;

class Add_cols_to_news
{
	public function up()
	{
		\DBUtil::add_fields('news', array(
			'filename' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'filename_sys' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'file_source' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('news', array(
			'filename'
,			'filename_sys'
,			'file_source'

		));
	}
}