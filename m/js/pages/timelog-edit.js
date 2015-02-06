$(document).on('pageinit', '#timelogEditPage', function() {
	
	// Submit login form
	$('#timelogEditForm').submit(function(e) {
		e.preventDefault();
		$.mobile.loading('show');
		
		var user = RPM.GetUser();
		
		var logDate = $('#logDate').val();
		var hours = $('#hours').val();
		var description = $('#description').val();
		var taskId = $('#task').val();
		var timelogId = RPM.GetParam('timelog');
		
		RPM.SendApiRequest('timelog/edit', {
			"log_date" : RPM.FormatDate(logDate),
			"description" : description,
			"hours" : RPM.Pad(hours, 2) + ':00',
			"user" : user.Id,
			"task_id" : taskId,
			"id" : timelogId
		}, 'POST', function(resp) {
			if (!resp.success) {
				$('.message', $.mobile.activePage).html(resp.message);
				$('.message', $.mobile.activePage).fadeIn();
				return false;
			}
			
			$.mobile.loading('hide');
			history.back();
			return false;
		});
		
	});
	
});

$(document).on('pageshow', '#timelogEditPage', function() {

	var currentUser = RPM.GetUser();
	var project = RPM.GetParam('project');
	var timelogId = RPM.GetParam('timelog');
		
	$.mobile.loading('show');
	
	if (timelogId) {
		
		// Load existing timelog
		RPM.SendApiRequest('get/timelog/' + timelogId, null, 'GET', function(timelog) {
		
			$('#logDate').val(timelog.logDate);
			$('#description').val(timelog.description);
			$('#hours').val(timelog.hours);
			$('#task').val(timelog.task.id);
			$('#hours').slider('refresh');
			if (timelog.user.id != currentUser.Id) {
				$('#saveTimelogButton').hide();
			} else {
				$('#saveTimelogButton').show();
			}
			$.mobile.loading('hide');
		});
	} else {
		$('#saveTimelogButton').show();
	}
	
	// Retrieve tasks for project
	RPM.SendApiRequest('get/task', {
				"project/project.id" : project,
				"sortasc" : "name",
				"status" : "In Progress"
			}, 'GET', function(tasks) {
			
			var html = '';
			for(var i in tasks) {
				var task = tasks[i];
				html += '<option value="' + task.id + '">' + task.name + '</option>';
			}
			$('#task').html(html);
			$('#task').selectmenu('refresh');
			$.mobile.loading('hide');
	});
	
	// set default date
	var d = new Date();
    var day = d.getDate();
    var month = d.getMonth() + 1; //Months are zero based
    var year = d.getFullYear();
	$('#logDate').val(RPM.Pad(month, 2) + "/" + RPM.Pad(day, 2) + "/" + year);
	
});