function loadProjects() {

	$.mobile.loading('show');
	RPM.SendApiRequest('get/project', {
			"projects.status" : 'In Progress',
			"sortdesc" : 'start_date',
			"limit" : RPM.NumPerPage + "," + RPM.CurrentOffset
		}, 'GET', function(projects) {
		var html = '';
		
		RPM.HasMoreRecords = (projects.length > 0);
		for(var i in projects) {
			var project = projects[i];
			
			html += '<li><a href="project.html?id=' 
				+ project.id + '" data-transition="slide"><h3>' 
				+ project.name + '</h3><p>' + project.projectNumber + '</p></a></li>';
		}
		$('#projectList').append(html);
		$('#projectList').listview('refresh');
		$.mobile.loading('hide');
	});
}

$(document).on('pageinit', '#projectsPage', function() {
	RPM.InfiniteScroll(loadProjects);
});


$(document).on('pageshow', '#projectsPage', function() {
	var user = RPM.GetUser();
	
	if (user.Role != 'Administrator') {
		$('#projectBack').hide();
	}
	
	RPM.InitInfiniteScroll();
	$('#projectList').empty();
	loadProjects();
});
