<?php
class Zend_View_Helper_DisplayDate {
	
	function displayDate( $timestamp, $format=Zend_Date::DATETIME_MEDIUM ) {
		$date = new Zend_Date( $timestamp, 'de_DE' );
		return $date->get( $format );
	}
	
}