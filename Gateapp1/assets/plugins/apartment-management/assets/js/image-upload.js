jQuery(document).ready(function($)
{
	//alert('hii');return false;
	jQuery('#upload_user_avatar_button').on("click",function(event)
	{		
		    event.preventDefault();
			var file_frame;
		    // If the media frame already exists, reopen it.
		    if ( file_frame ) {
		      file_frame.open();
		      return;
		    }

		    // Create the media frame.
		    file_frame = wp.media.frames.file_frame = wp.media({
		      title: jQuery( this ).data( 'uploader_title' ),
		      button: {
		        text: jQuery( this ).data( 'uploader_button_text' ),
		      },
		      multiple: false  // Set to true to allow multiple files to be selected
		    });

		    // When an image is selected, run a callback.
		    file_frame.on( 'select', function() {
		      // We set multiple to false so only get one image from the uploader
		      attachment = file_frame.state().get('selection').first().toJSON();
			file=attachment.url;
			var get_file_extension = file.substr( (file.lastIndexOf('.') +1) );
			   
			if($.inArray(get_file_extension, ['jpg','jpeg','png'])== -1)
			{
				   alert('jpg,jpeg,png File allowed ,'+get_file_extension+' file not allowed');
				   // file_frame.open();
					return false; 
			}
		    else
			{	
		      jQuery("#amgt_user_avatar_url").val(attachment.url);
		      $('#upload_user_avatar_preview img').attr('src',attachment.url);
		      // Do something with attachment.id and/or attachment.url here
			}  
		    });

		    // Finally, open the modal
		    file_frame.open();
	});
	
    jQuery('.upload_user_cover_button').on("click",function(event)
	{
		    event.preventDefault();
			var file_frame;
		    // If the media frame already exists, reopen it.
		    if ( file_frame ) {
		      file_frame.open();
		      return;
		    }

		    // Create the media frame.
		    file_frame = wp.media.frames.file_frame = wp.media({
		      title: jQuery( this ).data( 'uploader_title' ),
		      button: {
		        text: jQuery( this ).data( 'uploader_button_text' ),
		      },
		      multiple: false  // Set to true to allow multiple files to be selected
		    });
			
		    // When an image is selected, run a callback.
		    file_frame.on( 'select', function() {
		      // We set multiple to false so only get one image from the uploader
		      attachment = file_frame.state().get('selection').first().toJSON();
		     // alert(attachment.url);
			  file=attachment.url;
			var get_file_extension = file.substr( (file.lastIndexOf('.') +1) );
			  
			if($.inArray(get_file_extension, ['jpg','jpeg','png'])== -1)
			{
				   alert('jpg,jpeg,png File allowed ,'+get_file_extension+' file not allowed');
				 
					return false; 
			}
			else
			{	 
		      jQuery("#amgt_apartment_background_image").val(attachment.url);
		      $('#upload_apartment_cover_preview img').attr('src',attachment.url);
		      // Do something with attachment.id and/or attachment.url here
			}  
		    });

		    // Finally, open the modal
		    file_frame.open();
	});
});