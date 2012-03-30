<?php

class Games extends Zend_Db_Table_Abstract {
	
	protected $_name		= 'games';
	protected $_rowClass	= 'Game';
	
	protected $_dependentTables = array( 'GameXRoleXUsers', 'Forums' );
	protected $_referenceMap	= array(
		'User' => array(
			'columns' => array( 'gOwner' ),
			'refTableClass' => 'Users',
			'refColumns' => 'uID'
		),
		'Status' => array(
			'columns' => array( 'gStatus' ),
			'refTableClass' => 'Statuss',
			'refColumns' => 'sID'
		)
	);
	
	public function fetchLatest( $count = 10 ) {
		return $this->fetchAll( null, 'gID DESC', $count );
	}
	
}