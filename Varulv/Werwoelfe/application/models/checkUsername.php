<?php

function checkUsername( $username ) {
	$existingUsers = array( 'markus', 'knutsen' );
	if ($username == '' ) {
		return '';
	} elseif ( strlen($username) < 4 ) {
		return '<span class="error">Username zu kurz</span>';
	} elseif ( in_array( $username, $existingUsers ) ) {
		return '<span class="error">Username existiert schon</span>';
	} else {
		return '<span class="ok">OK</span>';
	}
}

$name = isset( $_GET['name'] ) ? $_GET['name'] : '';
echo checkUsername( trim( $name ) );
