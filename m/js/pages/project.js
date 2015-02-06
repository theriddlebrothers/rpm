$(document).on('pageinit', '#projectPage', function() {

});

$(document).on('pageshow', '#projectPage', function() {

		$.mobile.loading('show');
		var projectId = RPM.GetParam('id');
		var user = RPM.GetUser();
		
		RPM.SendApiRequest('get/project/' + projectId, null, 'GET', function(project) {
		
			// Display project
			$('h1', $.mobile.activePage).html(project.name);
			$('#company').html(project.company.name);
			$('#projectNumber').html(project.projectNumber);
			$('#retainedHours').html(project.retainer.hours);
			
			$('#timelogsLink').attr("href", "timelogs.html?project=" + project.id);
			$('#companyLink').attr("href", "company.html?id=" + project.company.id);
			
			if (user.Role == 'Administrator') {
				var p = $('#invoicesLink').parent();
				if (p.length > 0) {
					$('#invoicesLink').attr("href", "invoices.html?project=" + project.id);
					$('#invoicesLink').show();
				}
			} else {
				$('#invoicesLink').closest('li').remove();
			}
			
			$('#projectJumpNav').listview('refresh');
			
			// Footer link update
			$('.timelogEditLink').attr("href", "timelog-edit.html?project=" + projectId);
			
			$.mobile.loading('hide');
	});
	
});