(function($) {
  	"use strict";
  	
  	// Tabs
  	$(function() {
  	    $( "#theme-options" ).tabs();
  	});
  	
  	// Clear Buttons
  	$('.image-clear-button').click( function (e) {
  		$(this).siblings('input[type=text]').val(null);
  		$(this).siblings('input[type=hidden]').val(null);
  		$(this).siblings('.image-preview').remove();
  		
  		e.preventDefault();
  	});
  	
  	// Switch
  	$('.js-switch').each(function () {
  		var input = $(this);
  		var el 	  = $(this).parent();
  		if ($(input).is(':checked')) {
  			var newSwitch = '<div class="switch on"><div class="handle"></div></div>';
  		} else {
  			var newSwitch = '<div class="switch"><div class="handle"></div></div>';
  		}
  		
  		$(newSwitch).insertAfter(input).click(function () { 
  			$(this).toggleClass("on"); 
  			if (!$(input).is(':checked')) {
  				input.prop( "checked", true );
  			} else {
  				input.prop( "checked", false );
  			}
  		});
  		$('label.name', el).click(function () {
  			$('div.switch', el).toggleClass("on"); 
  		});
  	});
  	
  	// Save Button Position
  	$('#save-options-top').attr('style','right:'+ $('#wp-admin-bar-my-account').width() +'px');
  	
  	// Save Form
  	$('#save-options-bottom, #save-options-top').click(function (e) {
  		
  		// Data String Array
  		var dataString = new Array();
  		
  		// Get TinyMCE Value
  		if ( $('.wp-editor-wrap').length > 0 ) {
  			tinyMCE.triggerSave();
  		}
  		
  		// Data String Array - Populate
  		$('#theme-options [id^='+ js_options_data[5] +']').each(function () {
  			if ($(this).is('input[type=text]') || $(this).is('textarea') || $(this).is('input[type=hidden]') || $(this).is('select')) {
  				
  				var value = $(this).val();
  				
  				if ($.isArray(value)) {
  					value = value.toString();
  				}
  				
  				dataString.push(new Array($(this).attr('id'), value));
  			} else if ($(this).is('input[type=checkbox]:checked')) {
  				dataString.push(new Array($(this).attr('id'), 'true'));
  			} else if ($(this).is('input[type=radio]:checked')) {
  				dataString.push(new Array($(this).attr('name'), $(this).val()));
  			} else if ($(this).is('input[type=checkbox]')) {
  				dataString.push(new Array($(this).attr('id'), null));
  			} 
  		});
  		
  		// Ajax
  		jQuery.post(
  		    ajaxurl, 
  		    {
  		        'action': js_options_data[10] + '_save_options',
  		        'data'	:  dataString,
  		        'theme_options_nonce' : $('#theme_options_nonce').val()
  		    }, 
  		    function(msg){
  		    	
  		        // Disable Elements
  		        $('input, textarea').each(function () {
  		        	$(this).attr('disabled', 'disabled');
  		        });
  		        
  		        // Disable Main Wrapper 
  		        $('#theme-options-wrapper').attr('style', 'opacity: 0.35');
  		        
  		        // Save Confirmation
  		        if (msg != 'false') {
  		        	$('#options-saved').fadeIn();
  		        } else {
  		        	$('#options-error').fadeIn();
  		        }
  		        
  		        
  		        // Save Buttons Disable
  		        $("#save-options-bottom, #save-options-top").addClass('disabled').text(js_options_data[2]);
  		        
  		        // Timeout Actions
  		        setTimeout(function () {
  		        
  		        	// No Accident - Allow Leave
  		        	$(window).off('beforeunload');
  		        	
  		        	// Save Confirmation Fade Out
  		        	if (msg != 'false') {
  		        		$('#options-saved').fadeOut();
  		        	} else {
  		        		$('#options-error').fadeOut();
  		        	}
  		        	
  		        	// Enable Elements
  		        	$('input, textarea').each(function () {
  		        		$(this).removeAttr('disabled');
  		        	});
  		        	
  		        	// Enable Main Wrapper
  		        	$('#theme-options-wrapper').removeAttr('style');
  		        	
  		        	// Enable Save Buttonss
  		        	$("#save-options-bottom, #save-options-top").each(function () {
  		        		$(this).removeClass('disabled').text($(this).attr('title'));
  		        	});
  		        	
  		        }, 1500)
  		    }
  		);
  		
  		e.preventDefault();
  	});
  	
  	// Reset Options
  	$('#reset-options-bottom').click(function (e) {
  		
  		if (confirm(js_options_data[4])) {
  		
  			// Data String Array
  			var dataString = new Array();
  			
  			// Data String Array - Populate
  			$('#theme-options [id^='+ js_options_data[5] +']').each(function () {
  				if ($(this).is('input[type=radio]')) {
  					dataString.push(new Array($(this).attr('name')));
  				} else {
  					dataString.push(new Array($(this).attr('id')));
  				}
  			});
  			
  			jQuery.post(
  			    ajaxurl, 
  			    {
  			        'action': js_options_data[10] + '_reset_options',
  			        'data':   dataString
  			    }, 
  			    function(response){
  			        location.reload();
  			    }
  			);
  		}
  		e.preventDefault();
  	});
  	
  	// Backup Options
  	$('#backup').click(function (e) {
		
		if (confirm(js_options_data[8])) {
			var container = $(this).parents('.message');
			var dataString = new Array();
			var data = [];
			
			
			var options_count = 0;
			
			// Data String Array - Populate
			$('#theme-options [id^='+ js_options_data[5] +']').each(function () {
				if ($(this).is('input[type=text]') || $(this).is('textarea') || $(this).is('input[type=hidden]') || $(this).is('select')) {
					
					var value = $(this).val();
					
					if ($.isArray(value)) {
						value = value.toString();
					}
					
					data[ options_count ] = {
						'option'	: $(this).attr('id'),
						'value'		: value
					};
				} else if ($(this).is('input[type=checkbox]:checked')) {
					data[ options_count ] = {
						'option'	: $(this).attr('id'),
						'value'		: 'true'
					};
				} else if ($(this).is('input[type=radio]:checked')) {
					data[ options_count ] = {
						'option'	: $(this).attr('name'),
						'value'		: $(this).val()
					};
				} else if ($(this).is('input[type=checkbox]')) {
					data[ options_count ] = {
						'option'	: $(this).attr('id'),
						'value'		: null
					};
				}
				options_count++; 
			});
			
			dataString.push(new Array(js_options_data[5] + '_theme_options_backup_list'));
			dataString.push(new Array(js_options_data[5] + '_theme_options_backup_', JSON.stringify(data)));
			
			jQuery.post(
			    ajaxurl, 
			    {
			        'action': js_options_data[10] + '_backup_options',
			        'data':   dataString
			    }, 
			    function(response){
			        $(window).off("beforeunload");
			        location.reload();
			    }
			);
		}
  		e.preventDefault();
  	});
  	
  	// Delete Back-up
  	$('.delete-backup').click(function (e) {
  		
  		if (confirm(js_options_data[7])) {
  			var container = $(this).parents('.message');
  			var parent = $(this).parents('li');
  			var list = $(this).parents('.backup-list');
  			var dataString = new Array();
  			
  			dataString.push( new Array( js_options_data[5] + '_theme_options_backup_' + $(this).data('backup'), $(this).data('backup') ) );
  			
  			jQuery.post(
  			    ajaxurl, 
  			    {
  			        'action': js_options_data[10] + '_reset_options',
  			        'data':   dataString
  			    }, 
  			    function( response ){
  			        if ( list.children().length == 1 ) {
  			        	container.removeClass('with-list').addClass('no-list');
  			        	parent.remove();
  			        } else {
  			        	parent.remove();
  			        }
  			    }
  			);
  		}
  		e.preventDefault();
  	});
  	
  	// Restore Back-up
  	$('.restore-backup').click(function (e) {
  		if (confirm(js_options_data[6])) {
  			var dataString = new Array();
  			
  			dataString.push(new Array(js_options_data[5] + '_theme_options_backup_' + $(this).data('backup')));
  			
  			jQuery.post(
  			    ajaxurl, 
  			    {
  			        'action': js_options_data[10] + '_restore_options',
  			        'data':   dataString
  			    }, 
  			    function(response){
  			        $(window).off("beforeunload");
  			        location.reload();
  			    }
  			);
  		}
  		e.preventDefault();
  	});
  	
  	// Import
  	$('#import-options').click(function (e) {
  		
  		if (confirm(js_options_data[4])) {
  		
  			// Data String Array
  			var dataString = $('#import_field').val();
  			
  			jQuery.post(
  			    ajaxurl, 
  			    {
  			        'action': js_options_data[10] + '_import_options',
  			        'data':   dataString
  			    }, 
  			    function(response){
  			    	$(window).off("beforeunload");
  			        location.reload();
  			    }
  			);
  		}
  		e.preventDefault();
  	});
  	
  	// Fancy Select Boxes
    $(".select-chosen").chosen({width : '100%'});
    $('select.select-style').selectric({maxHeight: 170});
  	 
  	// Accident Prevent
  	$('input, textarea, select').on('change', function () {
  		$(window).on("beforeunload", function(event) {
  		    return js_options_data[3];
  		});
  	});
  	
  	$(document).ready( function() {
  		
  		// Buttonset
	  	$(function() {
	  	    $( ".buttons" ).buttonset();
	  	  });
		
		// Color Picker	
		$('.color-picker').wpColorPicker();
	
		// Function Upload Media
		$('.image-upload-button').click(function (e) {
			var el = $(this).parent();
			var button = $(this);
			e.preventDefault();
			var uploader = wp.media({
				title : button.data('upload-title'),
				button : {
					text : button.data('upload-button')
				},
				multiple : false
			})
			.on('select', function () {
				var selection = uploader.state().get('selection');
				var attachment = selection.first().toJSON();
				$('input[type=text]', el).val(attachment.url);
				$('input[type=hidden]', el).val(attachment.id);
				if (!el.hasClass('upload_file')) {
					if ($('img', el).length > 0) {
						$('.image-preview', el).attr('src', attachment.url);
					} else {
						$('<img src="'+ attachment.url +'" class="image-preview">').insertBefore($(':last-child', el));
						$('.image-clear-button', el).attr('style', 'display:inline-block');
					}
				}
			})
			.open();
		});
 	
  	});
  	
	 
})(jQuery);  