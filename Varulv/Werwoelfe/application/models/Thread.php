<?php
class Thread extends Zend_Db_Table_Row_Abstract {
	
	function getOwner() {
		return $this->findParentRow( 'Users' );
	}
	
	/**
	 * Liefert die Anzahl der Postings in diesem Forum zurück.
	 * @return int Anzahl Postings
	 */
	function countPostings() {
		return count( $this->getPostings() );
	}

	/**
	 * Liefert die Postings in diesem Thread als Zend_Db_Table_Rowset zurück.
	 * @return Zend_Db_Table_Rowset postings
	 */
	function getPostings() {
		$postings = new Postings();
		$select	= $postings->select();
		$select->where( "tID = ?", $this->tID );
		$rowset = $postings->fetchAll( $select );
		return $rowset;
	}
		
}