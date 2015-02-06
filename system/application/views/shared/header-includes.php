<!-- Styles -->
<link type="text/css" rel="stylesheet" href="/min/f=/css/blueprint/screen.css,/css/main.css,/js/jquery/ui/smoothness/jquery-ui-1.8.6.custom.css,/js/jquery/sexy-combo/lib/sexy-combo.css,/js/jquery/sexy-combo/skins/sexy/sexy.css,/css/process.css" />
<link type="text/css" rel="stylesheet" href="/min/f=/css/blueprint/print.css" media="print" />
<!--[if lt IE 8]><link type="text/css" rel="stylesheet" href="/min/f=/css/blueprint/ie.css" media="print" /><![endif]-->
<link rel="icon" type="image/ico" href="/favicon.ico" />
<style type="text/css" media="print">img { display:none; }</style> 

<!-- JS -->
<script type="text/javascript" src="/min/b=js&amp;f=/utility.js,/jquery/jquery-1.4.3.min.js,/jquery/ui/jquery-ui-1.8.6.custom.min.js,/jquery/jquery.cookie.js,/jquery/autocomplete/jquery.autocomplete.pack.js,/jquery/sexy-combo/lib/jquery.sexy-combo-2.1.2.pack.js,/jquery/jquery.tablednd.min.js,/jquery/jquery.timepicker.js,/jquery/jquery.tmpl.min.js"></script>


<script type="text/javascript">
	$(function() {
		$('.popup').click(function() {
			var answer = confirm('Are you sure you want to delete this item?');
			if (answer) {
				return true;
			}
			return false;
		});
		
		$('.datepicker').datepicker();
		
		$('.datetimepicker').datetimepicker({
			ampm: true,
			timeFormat: 'hh:mmtt',
			separator: ' @ ',
			stepMinute: 15
			
		});
		
		$("select.editable").sexyCombo();
		
		$('.help').click(function() {
			var id = $(this).attr("href");
			var content = $(id).html();
			$('#dialog').html(content);
			$('#dialog').dialog({
				modal : true,
				width: '500px'
			});
		});
		
	});
</script>