<?php

namespace common;

use \Medoo\Medoo;

class appModel extends  Medoo
{	

    public function __construct()
    {
        $mysql_medoo_conf = [
			'database_type' => 'mysql',
			'database_name' => 'nbdon',
			'server' => '127.0.0.1',			
			'username' => 'nbdon',
			'password' => 'daqionB2020',
		];		
        parent::__construct($mysql_medoo_conf);
    }	
}
