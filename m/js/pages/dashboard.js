$(document).on('pageinit', '#dashboardPage', function() {
	var user = RPM.GetUser();
	$('h1', $.mobile.activePage).html('Welcome, ' + user.Name);
});

$(document).on('pageshow', '#dashboardPage', function() {

	
});




