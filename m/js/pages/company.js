$(document).on('pageshow', '#companyPage', function() {

		$.mobile.loading('show');
		var id = RPM.GetParam('id');
		
		RPM.SendApiRequest('get/company/' + id, null, 'GET', function(company) {
		
			// Display project
			$('h1', $.mobile.activePage).html(company.name);
			
			var address = '';
			if (company.address) address += company.address + '<br />';
			if (company.city) address += company.city + ', ';
			if (company.state) address += company.state;
			if (company.zip) address += ' ' + company.zip;
			$('#address').html(address);
		
			if (company.phone) {
				$('#phone a').attr("href", "tel:" + company.phone);
				$('#phone p').html(company.phone);
			} else {
				$('#phone').remove();
			}
			
			if (company.website) {
				$('#website a').attr("href", company.website);
				$('#website p').html(company.website);
			} else {
				$('#website').remove();
			}
			
			$('#companyActions').listview('refresh');
			
			$.mobile.loading('hide');
	});
	
});