/*	
	jQuery(function($){
  // Set all variables to be used in scope
  var frame,
      metaBox = $('#meta-box-id.postbox'), // Your meta box id here
      addImgLink = $('.upload-custom-img'),//metaBox.find('.upload-custom-img'),
      delImgLink = $('.delete-custom-img'),//metaBox.find( '.delete-custom-img'),
      imgContainer = $( '.custom-img-container'),//metaBox.find( '.custom-img-container'),
      imgIdInput = $('.upload-custom-img');// metaBox.find('.upload-custom-img');
  
  // ADD IMAGE LINK
  addImgLink.on( 'click', function( event ){
	 // $("body").on('click',addImgLink,function( event ){
// alert("Asd");
    event.preventDefault();
    
    // If the media frame already exists, reopen it.
    if ( frame ) {
      frame.open();
      return;
    }
    
    // Create a new media frame
    frame = wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: {
        text: 'Use this media'
      },
      multiple: true  // Set to true to allow multiple files to be selected
    });

    
    // When an image is selected in the media frame...
    frame.on( 'select', function() {	
      // Get media attachment details from the frame state
      var attachment = frame.state().get('selection').first().toJSON();		
	  // var attachment = frame.state().get('selection');
      // Send the attachment URL to our custom image input field.
      imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:50%;"/>' ); 
	// alert (JSON.stringify(attachment));
		// alert (attachment.toSource());
      // Send the attachment id to our hidden input
      imgIdInput.val( attachment.id );

      // Hide the add image link
      addImgLink.addClass( 'hidden' );

      // Unhide the remove image link
      delImgLink.removeClass( 'hidden' );
    });

    // Finally, open the modal on click
    frame.open();
  });
  
  
  // DELETE IMAGE LINK
  delImgLink.on( 'click', function( event ){

    event.preventDefault();

    // Clear out the preview image
    imgContainer.html( '' );

    // Un-hide the add image link
    addImgLink.removeClass( 'hidden' );

    // Hide the delete image link
    delImgLink.addClass( 'hidden' );

    // Delete the image id from the hidden input
    imgIdInput.val( '' );

  });

});  */

 function add_image(obj) {
            var parent=jQuery(obj).parent().parent('div.field_row');
            var inputField = jQuery(parent).find("input.meta_image_url");
 
            tb_show('', 'media-upload.php?TB_iframe=true');
 
            window.send_to_editor = function(html) {
                var url = jQuery(html).attr('src');//jQuery(html).find('img').attr('src');
                inputField.val(url);
                jQuery(parent)
                .find("div.image_wrap")
                .html('<img src="'+url+'" height="60" width="60" />');
 
                // inputField.closest('p').prev('.awdMetaImage').html('<img height=120 width=120 src="'+url+'"/><p>URL: '+ url + '</p>'); 
 
                tb_remove();
            };
 
            return false;  
        }
 
        function remove_field(obj) {
            var parent=jQuery(obj).parent().parent();
            //console.log(parent)
            parent.remove();
        }
 
        function add_field_row(id) {
            var row = jQuery('#master-row-'+id).html();
            jQuery(row).appendTo('#field_wrap_'+id);
        }


