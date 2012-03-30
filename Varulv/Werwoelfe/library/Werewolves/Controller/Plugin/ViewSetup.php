<?php

class Werewolves_Controller_Plugin_ViewSetup extends Zend_Controller_Plugin_Abstract {
	
	/**
 	 * @var Zend_View
 	 */
	protected $_view;

	public function dispatchLoopStartup( Zend_Controller_Request_Abstract $request ) {
		
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper( 'viewRenderer' );
		$viewRenderer->init();
		
		$view = $viewRenderer->view;
		$this->_view = $view;
		
		$view->originalModule = $request->getModuleName();
		$view->originalController = $request->getControllerName();
		$view->originalAction = $request->getActionName();
		
		$view->doctype('XHTML1_STRICT');
		
		$prefix = 'Werewolves_View_Helper';
		$dir = dirname(__FILE__).'/../../View/Helper';
		$view->addHelperPath( $dir, $prefix );

		$view->headMeta()->setName('Content-Type', 'text/html;charset=utf-8' );
		$view->headTitle( 'Varulv - v0.1' );
		$view->headLink()->appendStylesheet( $view->baseUrl().'/css/site.css' );
		
		$view->headScript()->appendFile( $view->baseUrl().'/js/mootools-core-1.3-full-compat.js', 'text/javascript' );
		$view->headScript()->appendFile( $view->baseUrl().'/js/mootools-more.js', 'text/javascript' );
		$view->headScript()->appendFile( $view->baseUrl().'/js/mootools-own.js', 'text/javascript' );
		
	}
	
	public function postDispatch( Zend_Controller_Request_Abstract $request ) {
		
		if ( !$request->isDispatched() ) {
			return;
		}
		
		$view = $this->_view;
		
		if ( count( $view->headTitle()->getValue() ) == 0 ) {
			$view->headTitle( $view->title );	
		}
		
		$view->headTitle()->setSeparator( ' - ' );
		
	}
	
}