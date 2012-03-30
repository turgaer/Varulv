<?php
class Users extends Zend_Db_Table_Abstract {
	
	protected $_name 		= 'users';
	protected $_rowClass	= 'User';
	
	protected $_dependentTables	= array( 'Games', 'GameXRoleXUsers', 'PostingXUsers', 'ForumXUsers' );
	
}