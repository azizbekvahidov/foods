<?php

// This is the database connection configuration.
return array(
	//'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	// uncomment the following lines to use a MySQL database
	
	'connectionString' => 'mysql:host=localhost;dbname=bulvar',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => '198923233171',
	'charset' => 'utf8',
    'enableParamLogging'=>true,
    'enableProfiling'=>true,
	
);