<?php

TestConfiguration::setup();

class TestConfiguration {
	
	static function setup() {
		
		// ZEND-Framework-Library
		$lib = realpath(dirname(__FILE__) . '/../library/' );
		
		set_include_path( get_include_path() . PATH_SEPARATOR . $lib );
		require_once_dirname(__FILE__) . '/../application/bootstrap.php';
		self::$bootstrap = new Bootstrap('test');
		
	}
	
	static function setupDatabase() {
		$db = Zend_Registry::get( 'db' );
		$db->query("DROP TABLE IF EXISTS games;");
		$db->query("CREATE TABLE games (
						gID INT( 6 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
						gName VARCHAR( 100 ) NOT NULL
					);");
		$db->query("INSERT INTO games ( gID, gName )
					VALUES 
						( '1', 'Krasses Spiel' ),
						( '2', 'Das legendäre Dorf Smore' )
					;");
		
		// continues
	}
	
}