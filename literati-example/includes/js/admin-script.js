(function( $ ) {
	'use strict';

	$(document).ready(function($) {
		$('#upload_image_button').click(function() {
			var custom_uploader = wp.media({
				title: 'Select Image',
				button: {
					text: 'Select Image'
				},
				multiple: false
			});
	
			custom_uploader.on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$('#image').val(attachment.url);
				$('#image-container').html('<img src="' + attachment.url + '" alt="Uploaded Image" style="max-width: 100px;">');
			});
	
			custom_uploader.open();
		});
	});
	
})( jQuery );
