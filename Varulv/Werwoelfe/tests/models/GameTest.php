<?php

require_once dirname(__FILE__).'/../TestConfiguration.php';
require_once '../application/models/Games.php';

class models_GamesTest extends PHPUnit_Framework_TestCase {
	
	public function setup() {
		TestConfiguration::setupDatabase();
	}
	
	public function testFetchAll() {
		$gamesFinder 	= new Games();
		$games			= $gamesFinder->fetchAll();
		
		$this->assertSame( 3, $games->count() );
	}
	
	public function testFetchLatestShouldFetchLatestEntriesInReverseOrder() {
		$gamesFinder	= new Games();
		$games		= $gamesFinder->fetchLatest( 1 );
		$this->assertSame( 1, $games->count() );
		$thisGame	= $games->current();
		$this->assertSame( 2, (int)$thisGame->gId );
	}
	
}