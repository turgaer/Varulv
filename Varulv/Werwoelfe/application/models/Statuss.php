<?php

class Statuss extends Zend_Db_Table_Abstract {
	
	protected $_name		= 'status';
	protected $_rowClass	= 'Status';
	
	protected $_dependentTables	= array( 'Games' );
	
}