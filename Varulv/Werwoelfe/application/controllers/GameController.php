<?php
class GameController extends Zend_Controller_Action {
	
	function init() {
		$ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
		$ajaxContext->addActionContext( 'index', 'html' );
		$ajaxContext->addActionContext( 'create', 'html' );
		$ajaxContext->addActionContext( 'create', 'json' );
		$ajaxContext->addActionContext( 'search', 'html' );
		$ajaxContext->addActionContext( 'search', 'json' );
		$ajaxContext->addActionContext( 'join', 'json' );
		$ajaxContext->addActionContext( 'list', 'html' );
		$ajaxContext->addActionContext( 'view', 'html' );
		$ajaxContext->initContext();
	}
	
	public function indexAction() {
		$auth 	= Zend_Auth::getInstance();
		$user 	= $auth->getIdentity();				
		$users 	= new Users();
		$user 	= $users->find( $user->uID )->current();
		$games	= new Games();
		$select	= $games->select()
						->where( "gOwner = ?", $user->uID );
		$this->view->ownGames = $games->fetchAll( $select );
		$games	= $user->findManyToManyRowset( 'Games', 'GameXRoleXUsers' );
		$this->view->inhGames = $games;
	}	
	
	public function createAction() {
		if ( $this->getRequest()->isPost() ) {
			$message = new Message();
			$formData = $this->_getFormDataCreate();
			$result = $this->_checkFieldsCreate( $formData );
			if ( !empty( $result ) ) {
				$message->setErrors( $result );
				$message->setTitle( "Fehler bei der Dorfgr&uuml;ndung" );
			} else {
				$games 	= new Games();
				$auth 	= Zend_Auth::getInstance();
				$user 	= $auth->getIdentity();				
				$config = Zend_Registry::get('config');

				// Das Spiel wird zusammengebaut und inserted...
				$dbData = array(
					'gCreated' 		=> time(),
					'gUpdated' 		=> time(),
					'gName'			=> $formData['f_name'],
					'gOwner'		=> $user->uID,
					'gStatus'		=> 1,
					'gPlayers'		=> $formData['f_play'],
					'gPass'			=> md5( $config->auth->salt . $formData['f_pass'] ),
					'gRdb'			=> $formData['f_rdb'],
					'gRww'			=> $formData['f_rww'],
					'gRoww'			=> $formData['f_roww'],
					'gRdg'			=> $formData['f_rdg'],
					'gRjg'			=> $formData['f_rjg'],
					'gRhx'			=> $formData['f_rhx'],
					'gRam'			=> $formData['f_ram'],
					'gRws'			=> $formData['f_rws'],
					'gRwst'			=> $formData['f_rwst'],
					'gRmm'			=> $formData['f_rmm'],
					'gRoles'		=> $formData['f_roles'],
					'gMaxboards'	=> $formData['f_maxboards'],
					'gEditable'		=> $formData['f_editable'],
					'gEditstr'		=> $formData['f_editstring'],
					'gSingleGame'	=> $formData['f_singlegame'],
					'gGhosts'		=> $formData['f_ghosts'],
					'gDesc'			=> $formData['f_desc']																			
				);
				$gID = $games->insert( $dbData );
				
				// Wir erstellen ein allgemeines Forum für das Spiel...
				$forums = new Forums();
				$dbData = array(
					'fName'			=> "Allgemein",
					'fGame'			=> $gID,
					'fCat'			=> 0,
					'fOpen'			=> 1
				);
				$fID = $forums->insert( $dbData );
				
				$message->setTitle( "Gr&uuml;ndung erfolgreich" );
				$message->setText( "Dein Dorf wurde gegr&uuml;ndet. Du kannst nun Besucher in Dein Dorf einladen
									und andere Bewohner k&ouml;nnen Dein Dorf &uuml;ber die Suche finden." );
			}
			$this->view->message = $message;
		} else {
			// Bei html-Aufruf das Formular zur Spielerstellung anzeigen
		}
	}
	
	public function searchAction() {
		if ( $this->getRequest()->isPost() ) {
			$formData	= $this->_getFormDataSearch();
			$result 	= $this->_checkFieldsSearch( $formData );
			$message = new Message();
			if ( !empty( $result ) ) {
				$message->setErrors( $result );
				$message->setTitle( "Ung&uuml;tige Suchangaben" );
			} else {
				$message->setLoadHtml( $this->view->url( array( 'controller'=>'game', 'action'=>'list', 'format'=>'html' ) ) );
			}
			$this->view->message = $message;
		} else {
			// Bei html-Aufruf das Suchformular anzeigen
		}
	}
	
	public function listAction() {
		$games		= new Games();
		$formData	= $this->_getFormDataSearch();
		// ToDo: Diese Counts sollten meines Erachtens eher ins Model, da sie an vielen Stellen benötigt
		// werden. Wie geht das im Zend?
		// ToDo: Außerdem könnte und sollte der JOIN ins Model... allerdings werden dann aus einer Abfrage
		// mehrere... (unperformanter) 
		$select		= $games->select()
							->setIntegrityCheck( false )
							->from( 'games', array( '*', 
													'( gRdb+gRam+gRhx+gRjg+gRws+gRwst+gRmm ) as countGood',
													'( gRoww+gRww+gRdg ) as countEvil',
													'( 0 ) as countNeutral' ) )
							->join( 'users', 'users.uID = games.gOwner', array( 'uName' ) );
		if ( !empty( $formData['f_name'] ) )
			$select->where( 'gName LIKE ?', '%'.$formData['f_name'].'%' );
		if ( !empty( $formData['f_playmin'] ) )
			$select->where( 'gPlayers > ?', $formData['f_playmin'] );
		if ( !empty( $formData['f_playmax'] ) )	
			$select->where( 'gPlayers < ?', $formData['f_playmax'] );		
			
		$this->view->games = $games->fetchAll( $select );
	}
	
	public function viewAction() {
		$games				= new Games();
		$game				= $games->find( $this->_request->getParam('id') )->current();
		$game->owner		= $game->findParentRow( 'Users' );
		$game->inhabitants 	= $game->findManyToManyRowset( 'Users', 'GameXRoleXUsers' );
		$game->forums		= $game->findDependentRowset('forums');
		$game->status		= $game->findParentRow( 'Statuss' );		
		
		$auth 	= Zend_Auth::getInstance();
		$user 	= $auth->getIdentity();	
		if ( $game->userIsInhabitant( $user ) )
			$this->view->userIsInhabitant = true;
		$this->view->game = $game;
	}
	
	public function joinAction() {
		if ( $this->getRequest()->isPost() ) {
			$message 	= new Message();
			$auth 		= Zend_Auth::getInstance();
			$user 		= $auth->getIdentity();				
			$players 	= new GameXRoleXUsers();
			$games		= new Games();
			$game		= $games->find( $this->_request->getParam('gID') )->current();
			
			$games = $players->getGamesForPlayer( $user->uID );
			$blockingGame = 0;
			for ( $i=0; $i<sizeof($games); $i++ ) {
				if ( $games[$i]->gSingleGame == 1 )
					$blockingGame = 1;
			}
			if ( $players->isPlaying( $user->uID ) && ($game->gSingleGame == 1 || $blockingGame) ) {
				$message->setTitle( "Einzug nicht erfolgreich" );
				$message->addError( "Du bist schon Einwohner in einem anderen Dorf und einer der beiden Dorfgr&uuml;nder
									m&ouml;chte nicht, daß Du einen Zweitwohnsitz besitzt." );
			} else {
				$dbData = array(
					'uID' 		=> $user->uID,
					'gID' 		=> $this->_request->getParam('gID'),
					'rID'		=> 3,
					'dead'		=> 0
				);
				$id = $players->insert( $dbData );
				$message->setReloadWin( "createWindow('game_".$game->gID."','Dorf: ".$game->gName."','win_game_html','/game/view/format/html/id/".$game->gID."',400,600,400,600)" );
			}
			$this->view->message = $message;	
		}
	}
		
	private function _checkFieldsCreate( $data ) {
		$result = array();

		$alphaChain = new Zend_Validate();
		$alphaChain->addValidator( new Zend_Validate_StringLength( 5, 99 ) )
				   ->addValidator( new Zend_Validate_Alpha( true ) );
		$alnumChain = new Zend_Validate();
		$alnumChain->addValidator( new Zend_Validate_StringLength( 4, 8 ) )
				   ->addValidator( new Zend_Validate_Alnum() );
		$numChain = new Zend_Validate();
		$numChain->addValidator( new Zend_Validate_Between( 6, 99 ) );
		$digitChain = new Zend_Validate();
		$digitChain->addValidator( new Zend_Validate_Digits() );
				   
		if ( !$alphaChain->isValid( $data['f_name'] ) ) {
			array_push ( $result, "Dein Dorf muss einen Namen bestehend aus Buchstaben und Leerzeichen von einer Anzahl zwischen 5 und 99 bekommen." );
		}
		
		if ( !$alnumChain->isValid( $data['f_pass']) && !empty($data['f_pass']) ) {
			array_push ( $result, "Das Passwort darf nur alphanumerische Zeichen enthalten und muss zwischen 4 und 8 Zeichen enthalten." );
		}
		
		if ( !$numChain->isValid( $data['f_play'] ) ) {
			array_push ( $result, "Die Spielerzahl muss zwischen 6 und 99 Personen liegen." );
		}

		if ( !$digitChain->isValid( $data['f_rdb'] ) ) {
			array_push ( $result, "Ung&uuml;ltiger Wert im Feld 'Dorfbewohner'." );
		}
		if ( !$digitChain->isValid( $data['f_rww'] ) ) {
			array_push ( $result, "Ung&uuml;ltiger Wert im Feld 'Werwolf'." );
		}		
		if ( !$digitChain->isValid( $data['f_rjg'] ) ) {
			array_push ( $result, "Ung&uuml;ltiger Wert im Feld 'J&auml;ger'." );
		}
		if ( !$digitChain->isValid( $data['f_rhx'] ) ) {
			array_push ( $result, "Ung&uuml;ltiger Wert im Feld 'Hexe'." );
		}
		if ( !$digitChain->isValid( $data['f_maxboards'] ) ) {
			array_push ( $result, "Ung&uuml;ltiger Wert im Feld f&uuml;r die maximale Forenanzahl." );
		}
		
		$roles = 0;
		$roles += $data['f_rdb'];
		$roles += $data['f_rww'];
		$roles += $data['f_rjg'];
		$roles += $data['f_rhx'];
		if ( $data['f_roww'] == 1 ) $roles++;
		if ( $data['f_rdg'] == 1 ) $roles++;
		if ( $data['f_ram'] == 1 ) $roles++;
		if ( $data['f_rws'] == 1 ) $roles++;
		if ( $data['f_rwst'] == 1 ) $roles++;
		if ( $data['f_rmm'] == 1 ) $roles++;
		
		if ( $data['f_play'] != $roles ) {
			array_push ( $result, "Die Spielerzahl muss gleich der Summe aller Rollen sein." );
		}
				
		return $result;
	}
	
	private function _getFormDataCreate() {
		$data = array();
        $filterChain = new Zend_Filter;
        $filterChain->addFilter(new Zend_Filter_StripTags);
        $filterChain->addFilter(new Zend_Filter_StringTrim);
        
        $data['f_name'] 		= $filterChain->filter( $this->_request->getPost('f_name') );
        $data['f_play'] 		= $filterChain->filter( $this->_request->getPost('f_play') );
        $data['f_pass'] 		= $filterChain->filter( $this->_request->getPost('f_pass') );
        $data['f_rdb'] 			= $filterChain->filter( $this->_request->getPost('f_rdb') );
        $data['f_rww'] 			= $filterChain->filter( $this->_request->getPost('f_rww') );
        $data['f_roww'] 		= ($this->_request->getPost('f_roww') == "on") ? 1 : 0;
        $data['f_rdg'] 			= ($this->_request->getPost('f_rdg') == "on") ? 1 : 0;
        $data['f_rjg'] 			= $filterChain->filter( $this->_request->getPost('f_rjg') );
        $data['f_rhx'] 			= $filterChain->filter( $this->_request->getPost('f_rhx') );
        $data['f_ram'] 			= ($this->_request->getPost('f_ram') == "on") ? 1 : 0;
        $data['f_rws'] 			= ($this->_request->getPost('f_rws') == "on") ? 1 : 0;
        $data['f_rwst']			= ($this->_request->getPost('f_rwst') == "on") ? 1 : 0;
        $data['f_rmm'] 			= ($this->_request->getPost('f_rmm') == "on") ? 1 : 0;
        //$data['f_roles'] 		= $this->_request->getPost('f_roles');
        $data['f_roles'] 		= 1;
        //$data['f_maxboards'] 	= $filterChain->filter( $this->_request->getPost('f_maxboards') );
        $data['f_maxboards'] 	= 99;
        //$data['f_editable'] 	= ($this->_request->getPost('f_editable') == "on") ? 1 : 0;
        $data['f_editable'] 	= 1;
        //$data['f_editstring'] 	= ($this->_request->getPost('f_editstring') == "on") ? 1 : 0;
        $data['f_editstring'] 	= 1;
        $data['f_singlegame'] 	= ($this->_request->getPost('f_singlegame') == "on") ? 1 : 0;
        //$data['f_ghosts'] 		= ($this->_request->getPost('f_ghosts') == "on") ? 1 : 0;
        $data['f_ghosts']	 	= 1;
        $data['f_desc']			= $filterChain->filter( $this->_request->getPost('f_desc') );

        return $data;		
	}
	
	private function _getFormDataSearch() {
		$data = array();
        $filterChain = new Zend_Filter;
        $filterChain->addFilter(new Zend_Filter_StripTags);
        $filterChain->addFilter(new Zend_Filter_StringTrim);
		
        $data['f_name'] 		= $filterChain->filter( $this->_request->getPost('f_name') );
        $data['f_playmin'] 		= $filterChain->filter( $this->_request->getPost('f_playmin') );
        $data['f_playmax'] 		= $filterChain->filter( $this->_request->getPost('f_playmax') );
        
        return $data;
	}
	
	private function _checkFieldsSearch( $data ) {
		$result = array();

		$alphaChain = new Zend_Validate();
		$alphaChain->addValidator( new Zend_Validate_Alpha() );
		$digitChain = new Zend_Validate();
		$digitChain->addValidator( new Zend_Validate_Digits() );
				   
		if ( !$alphaChain->isValid( $data['f_name'] ) && !empty( $data['f_name'] ) ) {
			array_push ( $result, "D&ouml;rf k&ouml;nnen nur Namen bestehend aus Buchstaben und Leerzeichen besitzen." );
		}
		
		if ( !$digitChain->isValid( $data['f_playmin'] ) && !empty( $data['f_playmin'] ) ) {
			array_push ( $result, "Ung&uuml;ltiger Wert im Feld 'minimale Spielerzahl'." );
		}
		if ( !$digitChain->isValid( $data['f_playmax'] ) && !empty( $data['f_playmax'] ) ) {
			array_push ( $result, "Ung&uuml;ltiger Wert im Feld 'maximale Spielerzahl'." );
		}
		
		return $result;
		
	}
	
}