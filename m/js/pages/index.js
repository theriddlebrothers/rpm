function getRedirectPage(user) {
	var page = null;
	
	if (user.Role == 'Administrator') {
		page = 'dashboard.html';
	} else {
		page = 'projects.html';
	}
	return page;
}

$(document).on('pageinit', '#loginPage', function() {

	// Submit login form
	$('#loginForm').submit(function(e) {
		$.mobile.loading('show');
		e.preventDefault();
		
		var username = $('#username').val();
		var password = $('#password').val();
		
		RPM.SendApiRequest('authenticate', {
			username : username,
			password : password
		}, 'POST', function(resp) {
			if ((resp).success) {
				var user = {
					Id : resp.id,
					Name : resp.name,
					AuthToken : resp.token,
					Email : resp.email,
					Role : resp.role
				};
				
				RPM.SetUser(user);
				
				$.mobile.loading('hide');
				
				var page = getRedirectPage(user);
				$.mobile.changePage(page, { transition: 'pop' });
				
			} else {
				$.mobile.loading('hide');
				$('.message', $.mobile.activePage).html(resp.message);
				$('.message', $.mobile.activePage).fadeIn();
			}
		});
		
	});
	
});

$(document).on('pageshow', '#loginPage', function() {

	
	var user = RPM.GetUser();
	
	if (user != null) {
	
		// Check if user is already logged in
		RPM.SendApiRequest('doauth', null, 'GET', function(resp) {
			if (resp.success) {
				var page = getRedirectPage(user);
				$.mobile.loading('hide');
				$.mobile.changePage(page, { transition: 'pop' });
			}
		})
	}
	
});




