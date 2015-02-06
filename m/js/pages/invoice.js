$(document).on('pageinit', '#invoicePage', function() {
	
});


$(document).on('pageshow', '#invoicePage', function() {
	var invoiceId = RPM.GetParam('id');
	
	$.mobile.loading('show');
	RPM.SendApiRequest('get/invoice/' + invoiceId, null, 'GET', function(invoice) {
		$('h1', $.mobile.activePage).html('Invoice #' + invoice.invoiceNumber);
		
		$('#invoiceDate', $.mobile.activePage).html(RPM.FormatDate(invoice.invoiceDate, null, 'mm/dd/YYYY'));
		$('#paymentTerms', $.mobile.activePage).html(invoice.terms);
		$('#projectName', $.mobile.activePage).html(invoice.project.name);
		$('#projectNumber', $.mobile.activePage).html(invoice.project.projectNumber);
		$('#invoiceAmount', $.mobile.activePage).html(RPM.FormatCurrency(invoice.amount));
		
		var html = '';
		for(var i in invoice.lineItems) {
			var item = invoice.lineItems[i];
			
			html += '<li>' + item.description + ' <span class="ui-li-count">' + RPM.FormatCurrency(invoice.amount) + '</span></li>';
		}
		$('#invoiceItems').html(html);
		$('#invoiceItems').listview('refresh');
		$.mobile.loading('hide');
	});
});
