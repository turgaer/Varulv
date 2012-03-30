<?php

class Threads extends Zend_Db_Table {
	
	protected $_name		= 'threads';
	protected $_rowClass	= 'Thread';
	
	protected $_dependentTables = array( 'Postings' );
	protected $_referenceMap	= array(
		'Forum' => array(
			'columns' => array( 'fID' ),
			'refTableClass' => 'Forums',
			'refColumns' => 'fID'
		),
		'User' => array(
			'columns' => array( 'tOwner' ),
			'refTableClass' => 'Users',
			'refColumns' => 'uID'
		)
	);
	
	public function fetchLatest( $count = 10 ) {
		return $this->fetchAll( null, 'tID DESC', $count );
	}	
	
}