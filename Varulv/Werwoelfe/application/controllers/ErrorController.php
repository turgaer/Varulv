<?php

class ErrorController extends Zend_Controller_Action {
	
	public function indexAction() {
	}
	
	public function errorAction() {
		$errors = $this->_getParam( 'error_handler' );
		Zend_Debug::dump( $errors );
	}
	
}
