<?php

class Roles extends Zend_Db_Table_Abstract {
	
	protected $_name		= 'roles';
	protected $_rowClass	= 'Role';
	
	protected $_dependentTables	= array( 'GameXRoleXUsers' );
	
	public function fetchLatest( $count = 10 ) {
		return $this->fetchAll( null, 'rID DESC', $count );
	}
	
}