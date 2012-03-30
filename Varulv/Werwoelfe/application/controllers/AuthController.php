<?php

class AuthController extends Zend_Controller_Action {
	
	function init() {
		$ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
		$ajaxContext->addActionContext( 'login', 'html' );
		$ajaxContext->addActionContext( 'identify', 'json' );
		$ajaxContext->initContext(); 
	}
	
	public function indexAction() {
		$this->_forward('login');
	}	
	
	public function loginAction() {
		// Zeige nur das Login-Formular
	}
	
	public function logoutAction() {
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$this->_redirect('/');
	}
	
	public function identifyAction() {
		if ( $this->getRequest()->isPost() ) {
			$message = new Message();
			$formData = $this->_getFormData();
			if ( empty( $formData['f_username'] ) || empty( $formData['f_password'] ) ) {
				$message->addError( "Bitte sowohl Pseudonym als auch Passwort angeben." );
				$message->setTitle( "T&uuml;r verschlossen" );
			} else {
				$authAdapter = $this->_getAuthAdapter( $formData );
				$auth = Zend_Auth::getInstance();
				$result = $auth->authenticate( $authAdapter );
				if ( !$result->isValid() ) {
					$message->addError( "Diese Angaben sind nicht korrekt." );
					$message->setTitle( "T&uuml;r verschlossen" );
				} else {
					$data = $authAdapter->getResultRowObject( null, 'uPass' );
					$auth->getStorage()->write( $data );
					$message->setText( "Anmeldung erfolgreich. Du trittst ins Freie.");
					$message->setTitle( "T&uuml;r offen" );
					$message->setRedirect( "/" );
				}
			}
			$this->view->message = $message;
		}
	}
	
	protected function _getAuthAdapter( $formData ) {
		$dbAdapter = Zend_Registry::get('db');
		$authAdapter = new Zend_Auth_Adapter_DbTable( $dbAdapter );
		$authAdapter->setTableName( 'users' )
					->setIdentityColumn( 'uName' )
					->setCredentialColumn( 'uPass' )
					->setCredentialTreatment( 'MD5(?)' );
		
					$config = Zend_Registry::get('config');
		$salt = $config->auth->salt;
		$password = $salt . $formData['f_password'];
		
		$authAdapter->setIdentity( $formData['f_username'] );
		$authAdapter->setCredential( $password );
		
		return $authAdapter;
	}
	
    protected function _getFormData() {
        $data = array();
        $filterChain = new Zend_Filter;
        $filterChain->addFilter(new Zend_Filter_StripTags);
        $filterChain->addFilter(new Zend_Filter_StringTrim);
        
        $data['f_username'] = $filterChain->filter( $this->_request->getPost('f_username') );
        $data['f_password'] = $filterChain->filter( $this->_request->getPost('f_password') );

        return $data;
    }
	
}