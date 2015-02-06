<script type="text/javascript">
	function checkLoginInterval() {
		$.getJSON('/cp/ajax/users/checklogin/', function(data) {
			var res = false;
			if (data.value != null) {
				if (data.value) {
					res = true;
				}
			}
			if (!res) {
				window.location.href = '/welcome/index/?logout=true&force=true';
			}
		});
	}
	
	$(function() {
		// periodically ensure user is still logged in - every 10 minutes
		var interval = <?php echo CHECK_LOGIN_MINUTES; ?> * (60 * 1000) 
		setInterval('checkLoginInterval();', interval)
		
		// Ajax search
		$('#query').focus(function() { $(this).val('') } );
		
		$('#query').autocomplete('/cp/ajax/search/');
		
		// navigate to selected item
		$("#query").result(function(event, data, formatted) {
			window.location.href = data[1];
		});
		
	});
	
</script>