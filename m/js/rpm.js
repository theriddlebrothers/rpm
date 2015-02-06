// See bottom of this file for other javascript includes for the application

var RPM = {

	// # of items for scrolling pages
	NumPerPage : 10,
	
	// Current listing page's offset
	CurrentOffset : 0,
	
	// Current listing options
	CurrentOptions : null,
	
	// Set to false if no more records to load
	HasMoreRecords: true,
	
	ServerUrl : location.protocol+'//'+location.hostname+(location.port ? ':' + location.port: ''),
	//ServerUrl : "http://sandbox.projectmanager.com",
    
    
    DisplayErrors : function(errList) {
    	if (errList.length == 0) return false;
    	
    	var err = '';
    	for(var i in errList) {
    		err += errList[i].Message + "\n";
    	}
    	alert(err);
    	
    	return true;
    },
    
    FormatCurrency : function(num) {
	    num = num.toString().replace(/\$|\,/g, '');
	    if (isNaN(num)) num = "0";
	    sign = (num == (num = Math.abs(num)));
	    num = Math.floor(num * 100 + 0.50000000001);
	    cents = num % 100;
	    num = Math.floor(num / 100).toString();
	    if (cents < 10) cents = "0" + cents;
	    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
	    num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
	    return (((sign) ? '' : '-') + '$' + num + '.' + cents);
	},
    
    /**
	 * Converts a date or string into a different date format and returns it as a string.
	 */
    FormatDate: function (dt, time, format) {
		
		if (format == undefined) {
			format = "YYYY-mm-dd HH:ii:ss";
		}
		
        var d = dt;

        // First parameter is a not date object.
        if (d.getMonth == undefined) {

			if (time == undefined) time = '00:00:00';
			
            // Take a date and time stamp and return the properly formatted date string
            var fullPickupDate = dt + ' ' + time;

            var pattern = new RegExp("(\\d{2})/(\\d{2})/(\\d{4}) (\\d{2}):(\\d{2})");
            var parts = fullPickupDate.match(pattern);
            var year = parseInt(parts[3]);
            var month = parseInt(parts[1], 10) - 1;
            var day = parseInt(parts[2], 10);
            var hour = parseInt(parts[4]);
            var minute = parseInt(parts[5]);
            
            d = new Date(year, month, day, hour, minute, 0);
            
        }
        
        var day = d.getDate();
        var month = d.getMonth() + 1; //Months are zero based
        var year = d.getFullYear();
        var hours = d.getHours();
        var minutes = d.getMinutes();
        var seconds = d.getSeconds();
        
        var ret = format;
        
        ret = ret.replace("YYYY", year);
        ret = ret.replace("mm", RPM.Pad(month, 2));
        ret = ret.replace("dd", RPM.Pad(day, 2));
        ret = ret.replace("HH", RPM.Pad(hours, 2));
        ret = ret.replace("ii", RPM.Pad(minutes, 2));
        ret = ret.replace("ss", RPM.Pad(seconds, 2));

        return ret;
    },
	
	/**
	 * Retrieve latitude/longitude comma separated string for
	 * current user's location.
	 */
	GetCurrentCoordinates : function(callback, error) {
		
		if (error == undefined) {
			error =  function() {
		    	alert('Could not retrieve current GPS location.');
		    }
		}
		
    	navigator.geolocation.getCurrentPosition(function(location) {
    		callback(location.coords.latitude + ', ' + location.coords.longitude);
    	});
	},

	GetParam : function(name) {
		var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	        hash = hashes[i].split('=');
	        vars.push(hash[0]);
	        vars[hash[0]] = hash[1];
	    }
	    return vars[name];
	},
	
	GetUser : function()
	{
		var str = window.localStorage.getItem("User");
		return JSON.parse(str);
	},
	
	InitInfiniteScroll : function() {
		RPM.CurrentOffset = 0;
		RPM.HasMoreRecords = true;
	},
	
	InfiniteScroll : function(callback) {
		RPM.CurrentOffset = 0;
		$(window).scroll(function(){
		    if($(document).height() > $(window).height())
		    {
		        if($(window).scrollTop() == $(document).height() - $(window).height()) {
		        	if (RPM.HasMoreRecords) {
		        		RPM.CurrentOffset += RPM.NumPerPage;
		         		callback();
		        	}
		        }
		    }
		});
	},
	
	Logout : function() {
		RPM.SetAuthToken(null);
		RPM.SetUser(null);
	},
	
    /**
	 * Pad a number with leading zeroes
	 */
    Pad: function (number, length) {
        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }
        return str;
    },

	SendApiRequest : function(path, data, method, success, error) {
	
		method = method || 'GET';
		
		// Direct to API controller
		if (path[0] != '/') path = '/' + path;
		
		// Must append trailing slash due to querystring parsing limitation
		var url =  RPM.ServerUrl + '/api' + path + '/';
		
		if (data == null) {
			data = {};
		}
		
		var user = RPM.GetUser();
		if (user != undefined) {
			data.token = user.AuthToken;
		}
        var serialized = data; //$.toDictionary(data);
    
        if (success == undefined) {
        	success = function(resp) {
                console.log(resp);
        	}
        }
        
        if (error == undefined) {
        	error = function(request, status, error) {
                alert('API error: ' + request.responseText);
                $.mobile.hidePageLoadingMsg();
                console.log(request);
                console.log(status);
                console.log(error);
        	}
        }
        
        $.ajax({
            type: method,
            url: url,
            cache: false,
            dataType: 'json',
            data: serialized,
           // crossDomain : true,
            //traditional: true,
            error: error,
            success: success
        });
	},
	
	SetUser : function(user)
	{
		window.localStorage.setItem("User", JSON.stringify(user));
	},
	
	Url : function(path) {
		if (path[0] != '/') path = '/' + path;
		return RPM.ServerUrl + path;
	}	
}


// Bootstrap to load all JS/CSS files in each page.
// In production these should be combined into a single JS file and this
// block removed for performance
$.ajaxSetup({async: false});
$.getScript('js/pages/index.js');
$.getScript('js/pages/dashboard.js');
$.getScript('js/pages/settings.js');
$.getScript('js/pages/project.js');
$.getScript('js/pages/projects.js');
$.getScript('js/pages/company.js');
$.getScript('js/pages/timelogs.js');
$.getScript('js/pages/timelog-edit.js');
$.getScript('js/pages/invoice.js');
$.getScript('js/pages/invoices.js');
$.ajaxSetup({async: true});