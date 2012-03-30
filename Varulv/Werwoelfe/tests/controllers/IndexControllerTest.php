<?php

require_once_dirname(__FILE__) . '/../TestConfiguration.php';

class controllers_indexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	public function setUp() {
		$bootstrap			= TestConfiguration::$bootstrap;
		$this->bootstrap 	= array( $bootstrap, 'configureFrontController' );
		parent::setUp();
	}
	
	public function testHomepageIsSuccessfulRequest() {
		$this->dispatch( '/' );
		$this->assertFalse( $this->resonse->isException() );
		$this->assertNotRedirect();
	}
	
	public function testHomepageDisplaysCorrectContent() {
		$this->dispatch( '/' );
		$this->assertQueryContentContains( 'h1', 'Werwölfe' );
		$this->assertQueryContentContains( 'h1', 'Aktuellste Spiele' );
		$this->assertQueryCount( 'td a', 2 );
		$this->assertQueryContentContains( 'td a', 'Smore' );
	}
	
}