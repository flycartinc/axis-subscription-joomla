/**
 * Setup (required for Joomla! 3)
 */
if(typeof(axisubs) == 'undefined') {
	var axisubs = {};
}
if(typeof(axisubs.jQuery) == 'undefined') {
	axisubs.jQuery = jQuery.noConflict();
}

if(typeof(axisubsURL) == 'undefined') {
	var axisubsURL = '';
}

function axisubsDoTask(url, container, form, msg, formdata) {

	(function($) {		
	//to make div compatible
	container = '#'+container;	

	// if url is present, do validation
	if (url && form) {
		var str = $(form).serialize();
		// execute Ajax request to server
		$.ajax({
			url : url,
			type : 'get',
			 cache: false,
             contentType: 'application/json; charset=utf-8',
             data: formdata,
             dataType: 'json',
             beforeSend: function() {
               	 $(container).before('<span class="wait"><img src="media/com_axisubs/images/loader.gif" alt="" /></span>');
                   },
             complete: function() {
            	 $('.wait').remove();
             },
			// data:{"elements":Json.toString(str)},
             success: function(json) {
            	if ($(container).length > 0) {            		
            		$(container).html(json.msg);
				}				
				return true;
			}
		});
	} else if (url && !form) {
		// execute Ajax request to server
		$.ajax({
			url : url,
			 type: 'get',
             cache: false,
             contentType: 'application/json; charset=utf-8',
             data: formdata,
             dataType: 'json',
             beforeSend: function() {
               	 $(container).before('<span class="wait"><img src="media/com_axisubs/images/loader.gif" alt="" /></span>');
                 },
             complete: function() {
            	 $('.wait').remove();
             	},
             success: function(json) {
            	 if ($(container).length > 0) {
            		$(container).html(json.msg);
				}				
			}
		});
	}
	})(axisubs.jQuery);
}