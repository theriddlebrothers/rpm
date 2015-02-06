/**
 * Add commas to a # for formatting
 */
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

var Dropbox = {

	
	getFolders : function(root, path, callback) {
		$.getJSON('/cp/ajax/get_folders/' + '?root='+root+'&folder=' + path, function(response) {
			callback(response);
		});
	},
	
	getProjectFiles : function(path, projectID, callback) {
		$.getJSON('/cp/ajax/get_project_files/' + projectID + '/?folder=' + path, function(response) {
			callback(response);
		});
	},
	
	getFolderDisplay : function(files, path, isRoot) {
		var ajaxUrl = '/cp/ajax/get_folders/';
		var row = '';
		var html = '';
		
		if (!isRoot) {
			// up a folder
			var upPath = path.split('/');
			upPath.pop();
			path = upPath.join('/');
			row = '<tr><td></td><td><a class="open" href="#' + path + '">..</a></td><td></td></tr>';
			html += row;
		}

		if(files.length > 0) {
			for(i=0; i<files.length; i++) {
				var file = files[i];
				if (file.is_dir) {
					row = '<tr><td><a class="open" ';
					row += 'href="#' + file.url + '"';
					row += '">' + file.name + '</a></td>' +
							'<td><a href="#' + file.relative_path + '" class="select icon folder">Select folder</a></td></tr>';
					html += row;
				}
			}
		} else {
			row = '<tr><td colspan="3">No files found.</td></tr>';
			html += row;
		}
		return html;
	},
	
	getFileDisplay : function(projectID, files, path, isRoot) {
		var ajaxUrl = '/cp/ajax/get_project_files/'+projectID;
		var row = '';
		var html = '';

		if (!isRoot) {
			// up a folder
			var upPath = path.split('/');
			upPath.pop();
			path = upPath.join('/');
			row = '<tr><td></td><td><a class="open" href="#' + path + '">..</a></td><td></td></tr>';
			html += row;
		}
		
		if(files.length > 0) {
			for(i=0; i<files.length; i++) {
				var file = files[i];
				row = '<tr><td>' + file.modified + '</td>' +
						'<td><a class="open" ';
						
				if (file.is_dir) {
					row += 'href="#' + file.url + '"';
				} else {
					row += 'href="' + ajaxUrl + '?dl=' + file.url + '" target="_blank" ';
				}
				row += '">' + file.name + '</a></td>' +
						'<td class="alignright">' + file.size + '</td></tr>';
				html += row;
			}
		} else {
			row = '<tr><td></td><td colspan="2">No files found.</td></tr>';
			html += row;
		}
		return html;
	}
	
}


