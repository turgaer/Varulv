<?php

class IndexController extends Zend_Controller_Action {
	
	public function indexAction() {
		$this->view->headTitle("Willkommen");
		$this->view->title = "Willkommen";
	}
	
	public function menuAction() {
		$auth = Zend_Auth::getInstance();
		
		$mainMenu = array(			array( 	'title'=>'Startseite', 
											'url'=>$this->view->url( array(), null, true) )
					);
		if ( $auth->hasIdentity() ) {
			array_push( $mainMenu, 	array( 	'title'=>'Spiele', 'url'=>'#',
											'js'=>'onClick="createWindow(\'game\',\'Deine D&ouml;rfer\',\'win_main_html\',\''.
													$this->view->url( array( 	'controller'=>'game', 
																				'action'=>'index',
																				'format'=>'html') )
													.'\',750,1500,750,1000)"') );               
			array_push( $mainMenu, 	array( 	'title'=>'Logout', 
											'url'=>$this->view->url( array( 	'controller'=>'auth', 
																				'action'=>'logout') )
										)										
			);
		}
		
		if ( !$auth->hasIdentity() ) {
			array_push( $mainMenu, 	array( 	'title'=>'Login', 'url'=>'#', 
											'js'=>'onClick="createWindow(\'login\',\'T&uuml;r aus Deiner H&uuml;tte\',\'win_main_html\',\'/auth/login/format/html/\',450,450,300,300)"') );
			array_push( $mainMenu,	array( 	'title'=>'Registrieren', 'url'=>'#', 
											'js'=>'onClick="createWindow(\'register\',\'Die Anmeldung im Dorf\',\'win_main_html\',\'/register/index/format/html/\',250,800,300,600)"') );
		}
		
		$this->view->menu = $mainMenu;
		$this->_helper->viewRenderer->setResponseSegment( 'menu' );
	}
	
	public function advertAction() {
		$this->_helper->viewRenderer->setResponseSegment( 'advert' );
	}
	
}