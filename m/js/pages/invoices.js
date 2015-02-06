function loadInvoices() {
	
	$.mobile.loading('show');
	
	var projectId = RPM.GetParam('project');
	
	var options = RPM.CurrentOptions;
	
	options["project/project.id"] = projectId;
	options["limit"] = RPM.NumPerPage + "," + RPM.CurrentOffset;
	
	RPM.CurrentOptions = options;
	
	RPM.SendApiRequest('get/invoice', RPM.CurrentOptions, 'GET', function(invoices) {
		var html = '';
		
		RPM.HasMoreRecords = (invoices.length > 0);
		for(var i in invoices) {
			var invoice = invoices[i];
			
			html += '<li><a href="invoice.html?id=' 
				+ invoice.id + '" data-transition="slide">' 
				+ '<h3>' + invoice.description + '</h3>' 
				+ '<p><span class="detail">' + invoice.invoiceNumber + '</span>'
				+ '<span class="detail">' + invoice.project.name + '</span></p>'
				+ '<p class="ui-li-aside"><strong>' + RPM.FormatCurrency(invoice.amount) + '</strong><br />'
				+ 'Due: ' + RPM.FormatDate(invoice.dueDate, null, "mm/dd/YYYY") + '<br />'
				+ invoice.status + '</p>'
				+ '</a></li>';
		}
		$('#invoiceList').append(html);
		$('#invoiceList').listview('refresh');
		$.mobile.loading('hide');
	});
}

$(document).on('pageinit', '#invoicesPage', function() {
	RPM.InfiniteScroll(loadInvoices);
	
	$('input[name="invoiceFilter"]').change(function() {
		RPM.CurrentOptions["invoices.status"] = $(this).val();
		RPM.InitInfiniteScroll();
		$('#invoiceList').empty();
		loadInvoices();
		history.back(-1);
	});
});


$(document).on('pageshow', '#invoicesPage', function() {
	RPM.InitInfiniteScroll();
	
	RPM.CurrentOptions = {
			"invoices.status" : null,
			"sortdesc" : 'invoice_date'
	};
		
	$('#invoiceList').empty();
	loadInvoices();
});
