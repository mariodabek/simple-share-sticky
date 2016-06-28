jQuery(document).ready( function() {
 
 	jQuery('#simple_share_sticky a').click(function(e) {
 		 event.preventDefault(e);
 		 share_url = jQuery(this).attr('href');
 		 popUp=window.open(share_url, 'popupwindow', 'scrollbars=yes,width=800,height=400');
 		 popUp.focus();
 		 return false;
 	})

 	jQuery('#simple_share_sticky.admin label').click(function() {
 		jQuery(this).next().click();
 	})

})
