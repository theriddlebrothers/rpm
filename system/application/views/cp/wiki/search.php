<h2>Wiki Search Results</h2>

<?php if (!$pages->all) : ?>
	<p>No results were found.</p>
<?php else : ?>
	<?php foreach($pages->all as $page) : ?>
		<h3><a href="<?php echo site_url('wiki/page/' . $page->name); ?>"><?php echo $page->name; ?></a></h3>
		
		<p><?php echo $page->content; ?></p>
			
		<p><a href="<?php echo site_url('wiki/edit/' . $page->name); ?>">Edit</a></p>
		
<?php endforeach; ?>
<?php endif; ?>

<hr />

<div class="span-16">
	<p></p>
</div>

<div class="span-8 last">
	<form action="<?php echo site_url('wiki/search'); ?>" method="post" id="search">
		<input type="text" class="floatleft text"  name="search" /> 
		<button class="floatleft">Search</button>
	</p>
</div>
