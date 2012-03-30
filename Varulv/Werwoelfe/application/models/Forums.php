<?php

class Forums extends Zend_Db_Table {
	
	protected $_name		= 'forums';
	protected $_rowClass	= 'Forum';
	
	protected $_dependentTables = array( 'Threads', 'ForumXUsers' );
	protected $_referenceMap	= array(
		'Game' => array(
			'columns' => array( 'fGame' ),
			'refTableClass' => 'Games',
			'refColumns' => 'gID'
		),
	);
	
	public function fetchLatest( $count = 10 ) {
		return $this->fetchAll( null, 'fID DESC', $count );
	}	
	
}