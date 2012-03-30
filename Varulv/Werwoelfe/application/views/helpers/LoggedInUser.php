<?php
class Zend_View_Helper_LoggedInUser {
	
	protected $view;
	
	function setView( $view ) {
		$this->view = $view;
	}
	
	function loggedInUser() {
		$auth = Zend_Auth::getInstance();
		if ( $auth->hasIdentity() ) {
			$user = $auth->getIdentity();
			$username = $this->view->escape( ucfirst( $user->uName) );
			$string = 'Angemeldet als '.$username;
		} else {
			$string = '<i>(nicht eingeloggt)</i>';
		}
		return $string;
	}
	
}