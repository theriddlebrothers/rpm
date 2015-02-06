/* Setup Interface */
jQuery.noConflict();
jQuery(function($) {
	
	var baseUrl = 'http://rpm.theriddlebrothers.com';
	
	new datepickr('log_date', { dateFormat: 'm/d/Y' });
	
	$('form').submit(function(e) {
		e.preventDefault();
	});
			
	// Check to see if user is logged in
	var cookie = readCookie('rpm');
	if (cookie != null) {
		$.post(baseUrl + '/api/checkauth', { "token": cookie }, function(response) {
			if (response.success) {
					$('#timesheet').show();
					$('#login').hide();
			} else {
				$('#timesheet').hide();
			}
		}, 'json');
	} else {
		$('#timesheet').hide();
	}
	
	// search tasks
	$('input[name="task"]').autocomplete(baseUrl + '/cp/tasks/ajax/search/').result(function(event, data, formatted) {
		var hidden = $('#task_id');
		hidden.val(data[1]);
	});
	
	// Authentication
	$('#login_button').click(function() {
		var username = $('input[name="username"]').val();
		var password = $('input[name="password"]').val();
		$.post(baseUrl + '/api/authenticate', { "username": username, "password": password }, function(response) {
		console.log(response);
			if (response.success) {
				// store oauth token
				console.log(response.token);
				createCookie('rpm', response.token, 360);
				$('#login').fadeOut(400, function() {
					hideMessage();
					$('#timesheet').show()
				});
			} else {
				showMessage(response.message);
			}
		}, 'json');
	});
	
	
	// Log Time
	$('#logtime').click(function() {
		var dateEl = $('input[name="log_date"]');
		var timeEl = $('input[name="hours"]');
		var taskEl = $('input[name="task_id"]');
		var descriptionEl = $('input[name="description"]');
		
		var date = dateEl.val();
		var time = timeEl.val();
		var task = taskEl.val();
		var description = descriptionEl.val();
		var token = readCookie('rpm');
		
		$.post(baseUrl + '/api/logtime', { 
				hours: time, 
				task_id: task, 
				log_date : date,
				description: description,
				token: token
			}, function(response) {
			if (response.success) {
				// Reset the form
				timeEl.val('');
				taskEl.val('');
				descriptionEl.val('');
				showMessage('Timelog has been created successfully.');
			} else {
				showMessage(response.message);
			}
		}, 'json');
	});
	
	/**
	 * Display a message
	 */
	function showMessage(message) {
		$('#message p').html(message);
		$('#message').fadeIn();
	}
	
	function hideMessage() {
		$('#message').fadeOut();
	}
	
	function createCookie(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	}
	
	function readCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}
	
	function eraseCookie(name) {
		createCookie(name,"",-1);
	}
	
});