<?php
class ForumController extends Zend_Controller_Action {
	
	function init() {
		$ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
		$ajaxContext->addActionContext( 'view', 'html' );
		$ajaxContext->initContext();
	}
	
	public function indexAction() {
		return $this->viewAction();
	}	
	
	public function viewAction() {
		$forumFactory		= new Forums();
		$forum				= $forumFactory->find( $this->_request->getParam('id') )->current();
		$forum->threads 	= $forum->findDependentRowset( "threads" );
		$this->view->forum 	= $forum;
	}
		
}