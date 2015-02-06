var timelogId = null;

function loadTimelogs() {

	var projectId = RPM.GetParam('project');
	
	var currentUser = RPM.GetUser();
	
	$.mobile.loading('show');
	RPM.SendApiRequest('get/timelog', {
			"task/project.id" : projectId,
			"sortdesc" : "log_date",
			"limit" : RPM.NumPerPage + "," + RPM.CurrentOffset
		}, 'GET', function(timelogs) {
		
		var lastDate = null;
		var html = '';
			
		RPM.HasMoreRecords = (timelogs.length > 0);
		for(var i in timelogs) {
			var timelog = timelogs[i];
			
			var newDate = timelog.logDate;
			
			if (newDate != lastDate) {
				html += '<li data-role="list-divider">' + timelog.logDate + '</li>';
			}
			
			lastDate = newDate;
			
			html += '<li id="timelog-' + timelog.id + '"><a href="timelog-edit.html?project=' + timelog.project.id + '&timelog=' + timelog.id + '" data-transition="slideup">' +
					'<h3>' + timelog.description + '</h3>' +
					'<p>' + timelog.user.name + '</p>' +
					'</a>';
					
			if (timelog.user.id == currentUser.Id) {
				html += '<a class="options" href="#timelogOptions" data-timelog="' + timelog.id + '" data-position-to="window"  data-inline="true" data-rel="popup">Timelog Options</a>';
			}					
			html += '</li>';
		}
		$('#timelogList').append(html);	
		$('#timelogList').listview('refresh');
		
		// Footer link update
		$('.timelogEditLink').attr("href", "timelog-edit.html?project=" + projectId);
		
		$.mobile.loading('hide');
	});
}

$(document).on('pageinit', '#timelogsPage', function() {
	
	offset = 0;
	
	// Timelog infinite scroll
	RPM.InfiniteScroll(loadTimelogs);
	
	// Get timelog id for context menu
	$('.options', '#timelogList').live('tap', function() {
		timelogId = $(this).data('timelog');
	});
	
	// Delete timelog
	$('#deleteTimelogLink').live('tap', function() {
	
		var projectId = RPM.GetParam('project'
	
		$.mobile.loading('show'););
	
		RPM.SendApiRequest('timelog/delete', {
			id : timelogId
		}, 'POST', function(resp) {
			$.mobile.loading('hide');
			if (resp.success) {
				$('#timelog-' + timelogId, '#timelogList').remove();
				$.mobile.changePage('timelogs.html?project=' + projectId);
			} else {
				alert(resp.message);			
			}
		});
	});
});

$(document).on('pageshow', '#timelogsPage', function() {
	RPM.InitInfiniteScroll();
	$('#timelogList').empty();
	loadTimelogs();
});
