<?php
class Message {
	
	const NOT_SET	 	= 0;
	const INFORMATION	= 1;	// Eine normale Infomeldung wird generiert.
	const WARNING		= 2;
	const ERROR			= 3;	// Eine normale Fehlermeldung wird generiert.
	const RELOAD_WIN	= 7;	// Aktives Fenster wird geschlossen und neu geöffnet. Etwas dirty. 
	const LOAD_HTML		= 8;	// Aktives Formular wird nochmal im HTML-Mode abgeschickt.
	const REDIRECT		= 9;	// Die komplette Seite wird neu geladen. Nur sinnvoll bei Login/Logout.
	
	var $title		= "";
	var $text		= "";
	var $url		= "";
	var $errors 	= array();
	var $statusCode	= 0;
	
	public function setTitle( $title ) {
		$this->title = $title;
	}
	
	public function setRedirect( $url ) {
		$this->url = $url;
		$this->statusCode = 9;
	}
	
	public function setLoadHtml( $url ) {
		$this->url = $url;
		$this->statusCode = 8;
	}
	
	public function setReloadWin( $url ) {
		$this->url = $url;
		$this->statusCode = 7;
	}
	
	public function setText( $text ) {
		$this->text = $text;
		if ( $this->statusCode == 0 )
			$this->statusCode = 1;
	}
	
	public function addError( $error ) {
		array_push( $this->errors, $error );
		$this->statusCode 	= 3;
	}
	
	public function setErrors( $array ) {
		$this->errors 		= $array;
		$this->statusCode 	= 3;
	}
	
}