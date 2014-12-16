/*
 * One Click Upload 1.0.0 - jQuery Plugin
 * Copyright (c) 2008 Michael Mitchell - http://www.michaelmitchell.co.nz
 */
(function($){
	$.fn.upload = function(settings) {
		/** Merge the users settings with our defaults */
		settings = jQuery.extend({
			name: 'file',
			method: 'post',
			enctype: 'multipart/form-data',
			action: 'act/import.php',
			onSubmit: function() {alert('Bitte warten Sie, bis das Hochladen der Datei best&auml;tigt wurde!');},
			onComplete: function() {alert('EVL erfolgreich auf die Plattform geladen!');}
		}, settings);

		var element = this;
		
		/** Give our input a unique id so we can find it later */
		var id = new Date().getTime().toString().substr(this.length-5);
		
		/** Upload Iframe */
		var iframe = $(
			'<iframe '+
				'id="iframe'+id+'" '+
				'name="iframe'+id+'"'+
				'></iframe>'
			); iframe.css({
			display: 'none'
		});
		
		/** Form */
		var form = $(
			'<form '+
				'method="'+settings.method+'" '+
				'enctype="'+settings.enctype+'" '+
				'action="'+settings.action+'" '+
				'target="iframe'+id+'"'+
			'></form>'
		); form.css({
			margin: 0,
			padding: 0
		});
		
		/** File Input */
		var input = $(
			'<input '+
				'name="'+settings.name+'" '+
				'type="file" '+
			'/>'
		); 
		input.css({
			position: 'relative',
			display: 'block',
			width: '10px', 
			textAlign: 'right',
			height:'15px',
			top: '0px',
			opacity: 0,
			zIndex:1001
		});
		
		/** Put everything together */
		form.append(input);
		element.after(form);
		element.after(iframe);
		
		/** Put our file input in the right place */
		input.css('marginTop', -element.height()-10+'px');

		/** Move the input with the mouse to make sure it get clicked! */
		var offset = element.offset();
		element.mousemove(function(e){
				if (navigator.appName.indexOf("Opera") != -1){ brCorr = -120;}
				else if (navigator.appName.indexOf("Explorer") != -1){ brCorr = -30;}
				else if (navigator.appName.indexOf("Netscape") != -1){ brCorr = -120;}
				else { brCorr = -120;}

			var top = e.pageY;
			var left = e.pageX;
			input.css({
				top: top-offset.top+Math.round((15-(top-offset.top))/2)+'px',
				left: left-offset.left+brCorr+'px'
			});
		});

		/** Submit the form after we have a file selected */
		input.change(function() {
			settings.onSubmit(); //do something before we upload?
			form.submit(); 
			
			/** Do something after we are finished uploading */
			iframe.load(function() {
				/** Get a response from the server in plain text */
				var myFrame = document.getElementById(iframe.attr('name'));
				var response = $(myFrame.contentWindow.document.body).text();
				settings.onComplete(response); //done :D
			});
		});
	};
})(jQuery);