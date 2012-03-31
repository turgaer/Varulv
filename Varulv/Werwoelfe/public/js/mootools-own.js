var baseURL = 'http://localhost:10089';

function getContent( container, url ) {
	new Request.HTML( {
        url: 		baseURL + url,
        evalResponse: true,
        onRequest: 	function() {
          				$( container ).set('text', 'Dorfvorsteher holt Daten...');
        			},
        onComplete: function( response ) {
        				$( container ).empty().adopt( response );
        			}
    }).send();
}

function createWindow( name, title, container, url, xmin, xmax, ymin, ymax ) {
	if ( $(container).getElementById( 'win_'+name ) ) {
		
	} else {
		var myWindow = new Element( 'div', {id: 'win_'+name, class: 'win', style: 'left: 100px; top: 100px; width: '+((xmin+xmax)/2)+'px; height: '+((ymin+ymax)/2)+'px'} );
		var myTopbar = new Element( 'div', {id: 'win_'+name+'_top', class: 'topbar'} ).set('html', title);
		myTopbar.inject( myWindow );
		var conContent = new Element( 'div', {id: 'win_'+name+'_con', class: 'scrollcon'} );
		conContent.inject( myWindow );
		new Element( 'div', {id: 'win_'+name+'_html', class: 'content'} ).inject( conContent );
		if ( xmin != xmax || ymin != ymax ) {
			new Element( 'div', {id: 'win_'+name+'_res', class: 'resizer'} ).inject( myWindow );
		}
		var remover = new Element( 'div', {id: 'win_'+name+'_rem', class: 'remover'} );
		remover.addEvent('click', function(){
		   this.parentNode.dispose();
		});
		remover.inject( myWindow );
		myWindow.inject( $(container) );
		
		$('win_'+name).makeDraggable({'handle':$('win_'+name+'_top')});
		if ( xmin != xmax || ymin != ymax ) {
			$('win_'+name).makeResizable({ 
		 		'handle':$('win_'+name+'_res') , 
		 		'limit':{'x':[xmin,xmax], 'y':[ymin,ymax]} 
			});
		}
		
	    new Request.HTML( {
	        url: 		baseURL + url,
	        evalResponse: true,
	        onRequest: 	function() {
	          				$( 'win_'+name+'_html' ).set('text', 'Dorfvorsteher holt Daten...');
	        			},
	        onComplete: function( response ) {
	        				$( 'win_'+name+'_html' ).empty().adopt( response );
	        			}
	    }).send();
	}

}

function submitForm( name, url, format, container ) {
	if ( format == "JSON" ) {
		var request = new Request.JSON( {
			url: 		url,
			onRequest: 	function() {
				$('form_'+name+'_result').getElementById('f_'+name+'_submit').disabled = 1;
				$('form_'+name+'_result').getElementById('form_'+name+'_messages').set('html', 'Dorfvorsteher pr&uuml;ft Inhalte...');
			},
			onSuccess: function( rObj, rText ) {
				if ( rObj.message.statusCode == '9' ) {
					window.location = rObj.message.url;
				} else if ( rObj.message.statusCode == '8' ) {
					submitForm( name, rObj.message.url, 'HTML', container );
				} else if ( rObj.message.statusCode == '7' ) {
					$('form_'+name).parentNode.parentNode.parentNode.dispose();
					eval( rObj.message.url );
				} else if ( rObj.message.statusCode == '3' ) {
					$('form_'+name+'_result').getElementById('f_'+name+'_submit').disabled = 0;
					var html = '<span class="error"><span class="subtitle">'+rObj.message.title+'</span>';
					html = html+array2html( rObj.message.errors )+'</span>';
					$('form_'+name+'_result').getElementById('form_'+name+'_messages').empty().set( 'html', html );
				} else {
					$('form_'+name).parentNode.empty().set('html', rObj.message.text );
				}
			}
		}).post( $('form_'+name) );
	} else {
	    new Request.HTML( {
	        url: 		url,
	        evalResponse: true,
	        onRequest: 	function() {
	    		$('form_'+name+'_result').getElementById('f_'+name+'_submit').disabled = 1;
	    		$('form_'+name+'_result').getElementById('form_'+name+'_messages').set('html', 'Dorfvorsteher pr&uuml;ft Inhalte...');
   			},
	        onComplete: function( response ) {
	    		$('form_'+name+'_result').getElementById('f_'+name+'_submit').disabled = 0;
	    		$('form_'+name+'_result').getElementById('form_'+name+'_messages').empty();
   				$(container).empty().adopt( response );
	        }
	    }).post( $('form_'+name) );
		
	}
}

function array2html( array ) {
	var html = "";
	for ( i=0; i<array.length; i++ ) {
		html = html+"<li>- "+array[i];
	}
	return html;
}

function tglField( field ) {
	if ( $(field).disabled == 0 ) { 
		$(field).checked = 0;
		$(field).disabled = 1;
	} else $(field).disabled = 0;
}

document.addEvent( 'domready' , function() {
	$('win_menu').makeDraggable({'handle':$('win_menu_top')});
	$('win_menu').makeResizable({ 
 		'handle':$('win_menu_res') , 
 		'limit':{'x':[220,400], 'y':[120,400]} 
 		});
} );