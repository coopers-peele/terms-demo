!function( $ ) {
	$.fn.users = function( method ) {

		var settings,
			table;

		// Public methods
		var methods = {
			init: function( options ) {
				settings = $.extend( true, {}, $.fn.users.defaults, options );

				return this.each(function() {
					var $this = $( this );

					$( ".filters input" ).keyup(function( event ) {
						event.stopPropagation();
						table.fnDraw();
					});

					$( ".filters select" ).change(function( event ) {
						event.stopPropagation();
						table.fnDraw();
					});

					table = $( "table.table", this ).dataTable( $.extend( true, {}, settings.datatables, {
						drawCallback: function( settings, json ) {
							$( this ).show();

							$( ".actions a.info" ).click(function( event ) {
								event.preventDefault();

								var tr = $( this ).closest( "tr" );
								$( tr ).addClass( "active" ).siblings().removeClass( "active" );

								$( ".user_info .content" ).hide();
								$( ".user_info .loading" ).show();

								$.ajax( {
									url:  $( this ).attr( "href" ),
									dataType: "html",
									success: function( user_info ) {
										$( ".user_info .content" ).html( user_info );
										$( ".user_info .loading" ).hide();
										$( ".user_info .content" ).show();
									}
								});
							});

						},
						ajax: {
							data: function( data ) {
									$( ".filters select, .filters input" ).each(function() {
									var name = $( this ).attr( "name" ),
										value = $( this ).val();

									data[name] = value;
								});
							}
						}
					}));
				});
			}
		};

		if ( methods[ method ] ) {
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ) );
		}
		else if  ( typeof method === "object" || !method ) {
			return methods.init.apply( this, arguments );
		}
		else {
			$.error( "Method " +  method + " does not exist in $.users." );
		}
	};

	$.fn.users.defaults = {
		datatables: {
			autoWidth: false,
			columns: [
				{ data: "username" },
				{ data: "last_login" },
				{ data: "tos" },
				{ data: "agreed_on" },
				{ data: "actions" }
			],
			columnDefs: [
				{ className: "actions", targets: [ 4 ] },
				{ orderable: false, targets: [ 4 ] }
			],
			filter: false,
			language: {
				url: "/bundles/cptermsdemo/datatables/" + cpterms_user_admin.locale + ".json"
			},
			orderable: true,
			orderCellsTop: true,
			paging: true,
			processing: true,
			serverSide: true,
			stripeClasses: []
		}
	};
} ( window.jQuery );

$( document ).ready(function() {
	$( ".users" ).users( cpterms_user_admin.users );
});