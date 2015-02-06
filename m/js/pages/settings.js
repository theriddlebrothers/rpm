$(document).on('pageinit', '#settingsDialog', function() {

	$('#logout').live('tap', function() {
		RPM.SetUser(null);
		$.mobile.changePage('index.html', { transition : 'pop', reload: true, reverse : true })
	});
	
});