<?php
class ThreadController extends Zend_Controller_Action {
	
	function init() {
		$ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
		$ajaxContext->addActionContext( 'view', 'html' );
		$ajaxContext->addActionContext( 'form', 'html' );
		$ajaxContext->initContext();
	}
	
	public function indexAction() {
		return $this->viewAction();
	}	
	
	public function formAction() {
		// Hier wird einfach nur das html für Erstellungsformular für einen Thread
		// zurückgeliefert.
	}
	
	public function viewAction() {
		$forums				= new Forums();
		$game				= $games->find( $this->_request->getParam('id') )->current();
		$game->owner		= $game->findParentRow( 'Users' );
		$game->inhabitants 	= $game->findManyToManyRowset( 'Users', 'GameXRoleXUsers' );
		$game->status		= $game->findParentRow( 'Statuss' );		
		
		$auth 	= Zend_Auth::getInstance();
		$user 	= $auth->getIdentity();	
		if ( $game->userIsInhabitant( $user ) )
			$this->view->userIsInhabitant = true;
		$this->view->game = $game;
	}
		
}