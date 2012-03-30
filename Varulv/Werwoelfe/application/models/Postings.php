<?php

class Postings extends Zend_Db_Table {
	
	protected $_name		= 'postings';
	protected $_rowClass	= 'Posting';
	
	protected $_dependentTables = array( 'PostingXUsers' );
	protected $_referenceMap	= array(
		'Thread' => array(
			'columns' => array( 'tID' ),
			'refTableClass' => 'Threads',
			'refColumns' => 'tID'
		),
	);
	
	public function fetchLatest( $count = 10 ) {
		return $this->fetchAll( null, 'pID DESC', $count );
	}	
	
}