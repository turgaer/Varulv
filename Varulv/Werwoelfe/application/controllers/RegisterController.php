<?php

class RegisterController extends Zend_Controller_Action {
	
	function init() {
		$ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
		$ajaxContext->addActionContext( 'index', 'html' );
		$ajaxContext->addActionContext( 'register', 'json' );
		$ajaxContext->initContext(); 
	}
	
	public function indexAction() {
		// Zeige nur das Formular zur Registrierung an...
	}	
	
	public function registerAction() {
		if ( $this->getRequest()->isPost() ) {
			$message = new Message();
			$formData = $this->_getFormData();
			$result = $this->_checkFields( $formData );
			if ( !empty( $result ) ) {
				$message->setErrors( $result );
				$message->setTitle( "Fehler bei der Registrierung" );
			} else {
				$users = new Users();
				
				$select = $users->select();
				$select->where( 'uName = ?', $formData['f_username'] );
				$rows = $users->fetchAll( $select );				
				if ( $rows->count() > 0 ) {
					$message->addError( "Dieses Pseudonym ist leider schon vergeben." );
					$message->setTitle( "Fehler bei der Registrierung" );
				} else {
					
					$select = $users->select();
					$select->where( 'uEmail = ?', $formData['f_email'] );
					$rows = $users->fetchAll( $select );
					
					if ( $rows->count() > 0 ) {
						$message->addError( "Diese eMail-Adresse ist schon in Verwendung. Wenn Du Dein Passwort
											 vergessen haben solltest, kannst Du es hier zur&uuml;cksetzen lassen." );
						$message->setTitle( "Fehler bei der Registrierung" );
					} else {
					
						$config = Zend_Registry::get('config');						
						$dbData = array(
							'uCreated' 	=> date('Y-m-d'),
							'uUpdated' 	=> date('Y-m-d'),
							'uName'		=> $formData['f_username'],
							'uReal'		=> $formData['f_realname'],
							'uPass'		=> md5( $config->auth->salt . $formData['f_pass1'] ),
							'uSex'		=> $formData['f_sex'],
							'uEmail'	=> $formData['f_email']
						);
						
						$id = $users->insert( $dbData );
						$message->setTitle( "Registrierung erfolgreich" );
						$message->setText( "Du wurdest erfolgreich registriert und hast damit Dein Haus im Dorf bezogen.<br><br>
											Du kannst nun dieses Fenster schlie&szlig;en, Dich einloggen und aus der T&uuml;r ins 
											Freie treten." );
					}
				}
			}
			$this->view->message = $message;
		} else {
			// Besser: Fehler-500
			echo "no data";
		}
	}
	
	protected function _checkFields( $data ) {
		$result = array();
		
		if ( $data['f_sex'] == "" ) {
			array_push ( $result, "Bitte w&auml;hlen Sie ein Geschlecht aus." );
		}
			
		if ( $data['f_pass1'] != $data['f_pass2']) {
			array_push ( $result, "Die Inhalte beider Passwortfelder sind nicht identisch." );
		}
		
		$alnumChain = new Zend_Validate();
		$alnumChain->addValidator( new Zend_Validate_StringLength( 6,15 ) )
				   ->addValidator( new Zend_Validate_Alnum() );
		if ( !$alnumChain->isValid( $data['f_pass1'] ) ) {
			array_push ( $result, "Das Passwort darf nur alphanumerische Zeichen enthalten und muss zwischen 6 und 15 Zeichen enthalten." );
		}
		
		$alnumChain->addValidator( new Zend_Validate_StringLength( 4,15 ) );
		if ( !$alnumChain->isValid( $data['f_username'] ) ) {
			array_push ( $result, "Der Username darf nur alphanumerische Zeichen enthalten und muss zwischen 4 und 15 Zeichen enthalten." );
		}
		
		$emailChain = new Zend_Validate();
		$emailChain->addValidator( new Zend_Validate_EmailAddress() );
		if ( !$emailChain->isValid( $data['f_email'] ) ) {
			array_push ( $result, "Die eMail-Adresse ist nicht g&uuml;ltig." );
		}
		
		return $result;
	}
	
    protected function _getFormData() {
        $data = array();
        $filterChain = new Zend_Filter;
        $filterChain->addFilter(new Zend_Filter_StripTags);
        $filterChain->addFilter(new Zend_Filter_StringTrim);
        
        $data['f_username'] = $filterChain->filter( $this->_request->getPost('f_username') );
        $data['f_pass1'] = $filterChain->filter( $this->_request->getPost('f_pass1') );
        $data['f_pass2'] = $filterChain->filter( $this->_request->getPost('f_pass2') );
        $data['f_email'] = $filterChain->filter( $this->_request->getPost('f_email') );
        $data['f_realname'] = $filterChain->filter( $this->_request->getPost('f_realname') );
        $data['f_sex'] = $this->_request->getPost('f_sex');

        return $data;
    }
		
}