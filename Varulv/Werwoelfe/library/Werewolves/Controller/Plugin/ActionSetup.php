<?php

class Werewolves_Controller_Plugin_ActionSetup extends Zend_Controller_Plugin_Abstract {
	
	public function dispatchLoopStartup( Zend_Controller_Request_Abstract $request ) {
		
		if ( !$request->isXmlHttpRequest() ) {
			$front = Zend_Controller_Front::getInstance();
			if ( !$front->hasPlugin( 'Zend_Controller_Plugin_ActionStack' ) ) {
				$actionStack = new Zend_Controller_Plugin_ActionStack();
				$front->registerPlugin( $actionStack, 97 );
			} else {
				$actionStack = $front->getPlugin( 'Zend_Controller_Plugin_ActionStack' );
			}
			
			$menuAction = clone($request);
			$menuAction->setActionName('menu')->setControllerName('index');
			$actionStack->pushStack( $menuAction );
			
			$advertAction = clone($request);
			$advertAction->setActionName('advert')->setControllerName('index');
			$actionStack->pushStack( $advertAction );
		}
			
	}
	
}