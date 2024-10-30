/*
 * Since 1.0.0
 *
 * Update 1.1.9
 *
 * The Preview Form for Contact Form 7
 *
 */
(function($){
	
	$('#preview-panel-tab').each(function(){
		var old = $('#wpcf7-form').val();
		
		$(this).on('click', function(e){
			e.preventDefault();

			var tab = $('#cf7_preview_tab_content'),
				content = $('#wpcf7-form').val();

			if( content!='' && content!=old ) {
				old = content;
				
				tab.html( tab.data('message') );
				
				let url = $('#wp-admin-bar-view-site a').attr('href') || '';
				if( url == '' ) {
					return;
				}
				
				$.post( url, {
					'action': 'cf7_preview_form',
					'post_id': $('#post_ID').val(),
					'content': content
				}, function(data) {
					// console.log('The server responded: ', response);
					if( data ) {
						tab.html(data);
					}
				} );
			}
		}).on('click','input, button, select, textarea',function(e){
			// set no click form
			e.preventDefault();
		});

		// Since 1.0.4
		$('#informationdiv').after($('.cf7-preview-postbox-donate').show());
	});

})(jQuery);