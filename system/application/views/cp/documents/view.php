<div id="document">

	<h1><?php echo $doc->title; ?></h1>
	
	<p class="actions">
		
		<a href="<?php echo site_url('documents/compile/' . $doc->id); ?>" class="button primary">View Document</a>
		Create Section: <?php echo form_dropdown('type', $types); ?>
	</p>
	
	<div id="saved" class="success message" style="display:none">
		Section order has been saved.
	</div>
	
	<div id="document" class="span-24 last">
	
		<?php if ($tree) : ?>
			<?php echo $tree; ?>
		<?php else : ?>
			<p>No sections have been added.</p>
		<?php endif; ?>
		
	</div>
</div>
<script type="text/javascript" src="/js/jquery/nested-sortable.js"></script>

<script type="text/javascript">

	$(document).ready(function(){
		$('select[name="type"]').change(function() {
			if ($(this).val() == '') return;
			window.location.href = '/cp/documents/section/<?php echo $doc->id; ?>/0/' + $(this).val();
		});
		
		$('.sortable').nestedSortable({
			disableNesting: 'no-nest',
			forcePlaceholderSize: true,
			handle: 'div',
			items: 'li',
			listType: 'ul',
			opacity: .6,
			placeholder: 'placeholder',
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div',
			stop : function() {
				// save it
				serialized = $('.sortable').nestedSortable('serialize');
			$.post('<?php echo site_url('documents/ax/section_sort'); ?>', { doc: '<?php echo $doc->id; ?>', sections : serialized }, function(response) {
					if (response.result) {
						$('#saved').fadeIn();
					} else {
						alert('An error occurred while saving the section order. Your changes have not been saved.');
					}
				}, 'json');
			}
		});
	});

</script>