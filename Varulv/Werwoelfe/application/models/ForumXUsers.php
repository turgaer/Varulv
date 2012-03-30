<?php

class ForumXUsers extends Zend_Db_Table_Abstract {
	
	protected $_name		= 'forumxuser';
	protected $_rowClass	= 'ForumXUser';
	
	protected $_referenceMap	= array(
		'User' => array(
			'columns' => array( 'uID' ),
			'refTableClass' => 'Users',
			'refColumns' => 'uID'
		),
		'Forum' => array(
			'columns' => array( 'fID' ),
			'refTableClass' => 'Forums',
			'refColumns' => 'fID'
		),
	);
	
	public function fetchLatest( $count = 10 ) {
		return $this->fetchAll( null, 'fxuID DESC', $count );
	}
	
	public function getForumsForPlayer( $uID ) {
		$forums = array();
		$rowset = $this->fetchAll( $this->select()->where( "uID = ?", $uID ) );
		while ( $player = $rowset->current() ) {
			$forums[] = $player->findParentRow( 'forums' );
			$rowset->next();
		}
		return $forums;
	}
		
}