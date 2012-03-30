<?php

class GameXRoleXUsers extends Zend_Db_Table_Abstract {
	
	protected $_name		= 'gamexrolexusers';
	protected $_rowClass	= 'GameXRoleXUser';
	
	protected $_referenceMap	= array(
		'User' => array(
			'columns' => array( 'uID' ),
			'refTableClass' => 'Users',
			'refColumns' => 'uID'
		),
		'Game' => array(
			'columns' => array( 'gID' ),
			'refTableClass' => 'Games',
			'refColumns' => 'gID'
		),
		'Role' => array(
			'columns' => array( 'rID' ),
			'refTableClass' => 'Roles',
			'refColumns' => 'rID'
		)
	);
	
	public function fetchLatest( $count = 10 ) {
		return $this->fetchAll( null, 'gxrxuID DESC', $count );
	}
	
	public function getGamesForPlayer( $uID ) {
		$games = array();
		$rowset = $this->fetchAll( $this->select()->where( "uID = ?", $uID ) );
		while ( $player = $rowset->current() ) {
			$games[] = $player->findParentRow( 'games' );
			$rowset->next();
		}
		return $games;
	}
	
	public function findPlayerInGame( $uID, $gID ) {
		return $this->fetchRow( $this->select()->where( "gID = ?", $gID)->where( "uID = ?", $uID ) );
	}
	
	public function isPlaying( $uID ) {
		// ToDo: Join mit Game-Tabelle... nur Spiele, wo Gründer das so wollten!
		$player = $this->fetchRow( $this->select()->where( "uID = ?", $uID)->where( "dead = ?", 0) );
		$isPlaying = !empty( $player );
		return $isPlaying;
	}
	
}