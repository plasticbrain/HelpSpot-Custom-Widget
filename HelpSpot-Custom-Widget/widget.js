//------------------------------------------------------------------------------
// Custom HelpSpot Widget
// (c) 2011 Mike Everhart // Eagle Web Assets, Inc. // mikeeverhart.net
//------------------------------------------------------------------------------
//
// Changelog
//------------------------------------------------------------------------------
// 	2011-08-27 
//		- Initial Version
//
//	2001-08-29
//		- Added a better function to center the elements on screen
//
//------------------------------------------------------------------------------

(function($){  
	
	$.fn.HelpSpotWidget = function(options) {  
  	
  	//--------------------------------------------------------------------------
		// Default Options
		//--------------------------------------------------------------------------
		var defaults = {  
			
			// URLs and Paths
			base: '', // The base location of where these files are installed -- include a traling slash!
			host: '', // The base location of your HelpSpot installation -- include a traling slash!
			faqs: '', // The url (not including the HOST) to your FAQs
			
						
			// Customization
			tab_type: 'support', // which tab image to show (support, feedback, help, questions)
			tab_color: '#000000', // what color to use for the tab's background
			tab_top: '30%', // the CSS "top" position of the tab
			tab_alignment: 'right', // where to position the tab on the page
			form: 'form.php', // what form.php file to use
			width: '500px', // the width of the "popup" form
			height: '575px', // the height of the "popup" form
			
			// Text Labels
			submit_button_text: 'Submit', // the text to use on the form submit button
			success_headline_text: 'Your Message Has Been Received!',
			success_body_text: 'We will review your submission and someone will reply to you soon.',
			
			// form field values
			default_first_name: '',
			default_last_name: '',
			default_email: '',
			default_phone: '',
			default_department: '', // the ID of department/category that will be automatically selected
			
			// CSS ID's
			overlay_id: 'custom_widget_overlay',
			widget_content_id : 'custom_widget_content',
			close_button_id: 'custom_widget_close',
			form_id: 'custom_widget_form',
			iframe_id: 'custom_widget_iframe',
			tab_id: 'custom_widget_tab',
						
			// Debug mode
			debug: false, // doesn't do anything at this point
			demo: false,
			
			// Callback functions
			onLoad: function(){},
			onShow: function(){},
			onHide: function(){},
			onError: function(){},
			onSuccess: function(){}
			
		};  
		var options = $.extend(defaults, options);  
		
		var overlay;
		var tab;
		var content;
		
		//--------------------------------------------------------------------------
		// function to return debug messages
		//--------------------------------------------------------------------------
		function debug(msg) {
    	if(options.debug && window.console){
      	console.log(msg);
      }
		}
		
		
		//--------------------------------------------------------------------------
		// Setup the plugin (Add any HTML elements that we need, etc)
		//--------------------------------------------------------------------------
		function setup() {
			
			// setup all of the HTML we need
			var html = '';
			
			// widget content
			html += "<div id='" + options.widget_content_id + "' style='width:" + options.width + "; height:" + options.height + ";'>\n";
					
				// close button
				html += "<a href='#' id='" + options.close_button_id + "'>Close</a>\n";
					
				// iframe that holds the form
				html += '<iframe src="' + options.base + options.form + '?' + makeQueryString(options) + '&rand=' + Math.floor(Math.random()*1000) + '" name="custom_widget_iframe" id="' + options.iframe_id + '" allowTransparency="true" scrolling="no" frameborder="0" class=""></iframe>' + "\n";		
			
			html += "</div>\n";
			html += "<!-- end custom widget content -->\n\n";
			
			// the tab
			html += "<div id='" + options.tab_id + "' class='" + options.tab_type + " " + options.tab_alignment + "' style='display:none; top: " + options.tab_top + "; background-color:" + options.tab_color + ";'></div>\n"
			
			// the overlay 
			html += "<div id='" + options.overlay_id + "' style=''></div>\n"; 
			html += "<!-- end custom widget overlay -->\n\n";
			
			// append all of our HTML to the <body> element
			$('body').append($(html));
			
			// "cache" the DOM elements for future use			
			overlay = $('#' + options.overlay_id);
			content = $('#' + options.widget_content_id);
			tab = $('#' + options.tab_id);
			
			// Show the tab
			tab.fadeIn(750);
			
			// lastly, call the callback function
			options.onLoad.call(this);
			return true;
		}
		
		//--------------------------------------------------------------------------
		// Show the form
		//--------------------------------------------------------------------------
		function showForm() {
			
			// show the overlay
			overlay.css({opacity:0}).show().animate({opacity: .5}, 300).addClass('showing');
			
			// show the content
			content.css({opacity:0}).show().cwCenter().animate({opacity: 1}, 300).addClass('showing');
			
			// call the callback function
			options.onShow.call(this);
			return true;
		}
		
		//--------------------------------------------------------------------------
		// Hide the form
		//--------------------------------------------------------------------------
		function hideForm() {
			
			// hide the content
			content.animate({opacity: 0}, 300, function(){
				$(this).removeClass('showing').hide();
			});
			
			// hide the overlay
			overlay.animate({opacity: 0}, 300, function(){
				$(this).removeClass('showing').hide();
			});
			
			// call the callback function
			options.onHide.call(this);
			return true;
		}
		
		//--------------------------------------------------------------------------
		// Funtion to create a query string from an array
		//--------------------------------------------------------------------------
		function makeQueryString(arr) {
    	var qs = "";
    	for( var q in arr ) {
    		qs += '&' + encodeURIComponent(q) + "=" + encodeURIComponent(arr[q]);
    	}
    	return qs.substring(1);
    }
		
		//--------------------------------------------------------------------------
		// Finally, we start the plugin 
		//--------------------------------------------------------------------------
		return this.each(function() {  
			obj = $(this); 
			
			setup();
			
			// When the tab is clicked show the form
			$('#' + options.tab_id).bind('click', function(){
				showForm();
				return false;
			});
			
			// Close the form when the close button is clicked
			$('#' + options.close_button_id).bind('click', function(){
				hideForm();
				return false;
			});
			
			// Close the form if the user clicks on the overlay
			overlay.bind('click', function(){
				hideForm();
				return false;
			});
			
		});  
		
		
	};  
})(jQuery);  

//---------------------------------------------------------------------------
// Center Elements
//---------------------------------------------------------------------------
(function($) {
	$.fn.cwCenter = function() {
		var el;
		return this.each(function(index) {
			if(index == 0) {
				el = $(this);
    		el.css("position","absolute");
    		el.css("top", (($(window).height() - el.outerHeight()) / 2) + $(window).scrollTop() + "px");
    		el.css("left", (($(window).width() - el.outerWidth()) / 2) + $(window).scrollLeft() + "px");
    	}
   	 });
  	};
})(jQuery);

