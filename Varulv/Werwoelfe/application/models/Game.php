<?php
class Game extends Zend_Db_Table_Row_Abstract {
	
	var $inhabitants;
	var $forums;
	var $owner;
	var $status;
	
	function userIsInhabitant( $user ) {
		$isInhabitant = false;
		while ( $this->inhabitants->valid() ) {
			if ( $user->uID == $this->inhabitants->current()->uID ) 
				$isInhabitant = true;
			$this->inhabitants->next();
		} 
		if ( $this->gOwner == $user->uID )
			$isInhabitant = true;
		return $isInhabitant;
	}
	
}