<div id="wiki">

	<?php echo $page->content; ?>
	
	<hr />
	
	<div class="span-16">
		<p><a href="<?php echo site_url('wiki/edit/' . $name); ?>">Edit this page</a></p>
	</div>
	
	<div class="span-8 last">
		<form action="<?php echo site_url('wiki/search'); ?>" method="post" id="search">
			<input type="text" class="floatleft text"  name="search" /> 
			<button class="floatleft">Search</button>
		</p>
	</div>

</div>