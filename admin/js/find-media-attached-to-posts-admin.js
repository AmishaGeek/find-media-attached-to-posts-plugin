(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(document).ready(function(){
		$(".fmacheckAll").click(function(){
			$(this).parents('.fma_chk_wp').find('input:checkbox').not(this).prop('checked', this.checked);
		});

		$('.fma_chk').on('click',function(){
			if($(this).parents('.fma_chk_wp').find('.fma_chk_js:checked').length == $(this).parents('.fma_chk_wp').find('.fma_chk_js').length){
				
				$(this).parents('.fma_chk_wp').find('.fmacheckAll').prop('checked',true);
			}else{
				$(this).parents('.fma_chk_wp').find('.fmacheckAll').prop('checked',false);
			}
		});

		$('body').on('click', '.delete-attachment', function(e) {
			e.preventDefault();
	
			// get the URL parameter called "param"
			const urlParams = new URLSearchParams(window.location.search);
			const post_id = urlParams.get('item');
			if(post_id){
				jQuery.ajax({
					type : "post",
					dataType : "json",
					url : fma_ajax_object.ajax_url,
					data : { 
						action: "fma_prevent_delete_attachment", 
						id : post_id,
					},
					success: function(response) {     
						if( response.result == true ) {
							alert(response.message);
						}
					},
				});    
			}
		});
	});
})( jQuery );
