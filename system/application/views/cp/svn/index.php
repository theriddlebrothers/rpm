<div id="listSettings">

	<h1>SVN: <?php echo $repname; ?></h1>
	
	<div class="loading">
		<img src="/images/loading.gif" alt= "Loading..." />
		Loading...
	</div>
	
	<iframe id="websvn" width="100%" height="100%" class="autoHeight" src="/websvn/listing.php?repname=<?php echo $repname; ?>" border="0"></iframe>
	
</div>

<script type="text/javascript">
	$(function() {
		$('iframe').load(function() {
			$('.loading').slideUp();
		});
	});
</script>