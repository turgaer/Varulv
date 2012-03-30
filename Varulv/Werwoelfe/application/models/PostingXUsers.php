<?php

class PostingXUsers extends Zend_Db_Table_Abstract {
	
	protected $_name		= 'postingxusers';
	protected $_rowClass	= 'PostingXUser';
	
	protected $_referenceMap	= array(
		'User' => array(
			'columns' => array( 'uID' ),
			'refTableClass' => 'Users',
			'refColumns' => 'uID'
		),
		'Posting' => array(
			'columns' => array( 'pID' ),
			'refTableClass' => 'Postings',
			'refColumns' => 'pID'
		),
	);
	
	public function fetchLatest( $count = 10 ) {
		return $this->fetchAll( null, 'pxuID DESC', $count );
	}
	
	public function getPostingsForPlayer( $uID ) {
		$postings = array();
		$rowset = $this->fetchAll( $this->select()->where( "uID = ?", $uID ) );
		while ( $player = $rowset->current() ) {
			$postings[] = $player->findParentRow( 'postings' );
			$rowset->next();
		}
		return $postings;
	}
		
}