<?php
class Forum extends Zend_Db_Table_Row_Abstract {
	
	var $threads;
	
	/**
	 * Liefert die Anzahl der Threads in diesem Forum zurück.
	 * @return int Anzahl Threads
	 */
	function countThreads() {
		return count( $this->getThreads() );
	}

	function isVisibleForUser() {
		if ( $this->fVisible )
			return true;
		else {
			$auth 	= Zend_Auth::getInstance();
			$user 	= $auth->getIdentity();
			$fxu = new ForumXUsers();
			$select = $fxu->select();
			$select->where( "fID = ?", $this->fID );
			$select->where( "uID = ?", $user->uID );
			$row = $fxu->fetchRow();
			if ( !empty($row) )
					return true;
			else	return false;
		}
	}
	
	/**
	 * Liefert die Anzahl der Postings in allen Threads in diesem Forum zurück.
	 * @return int Anzahl Postings
	 */
	function countPostings() {
		$threads = $this->getThreads();
		$countPostings = 0;
		foreach ($threads as $thread) {
 			$countPostings += $thread->countPostings();
		}
		return $countPostings;
	}
	
	/**
	 * Liefert die Threads in diesem Forum als Zend_Db_Table_Rowset zurück.
	 * @return Zend_Db_Table_Rowset threads
	 */
	function getThreads() {
		$threads 	= new Threads();
		$select	= $threads->select();
		$select->where( "fID = ?", $this->fID );
		$rowset = $threads->fetchAll( $select );
		return $rowset;
	}
		
}