$( document ).ready(function() {
	$( document ).ajaxError(function( event, jqXHR ) {
		if ( 403 === jqXHR.status ) {
			console.log( "reload" );
			window.location.reload();
		}
	});
});
