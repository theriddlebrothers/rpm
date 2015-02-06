<div id="document">

	<h1><?php echo $doc->title; ?></h1>
	
	<form action="" method="POST">

		<?php $this->load->view('cp/documents/section', array('section_dropdown'=>$section_dropdown)); ?>
		
		<p>
			<label>Section Content</label><br />
			<textarea name="content" class="text"><?php echo postback($section, 'doccontent', 'content'); ?></textarea>
			<br /><span class="quiet">Section content formatting can be accomplished using <a target="_blank" href="http://michelf.com/projects/php-markdown/extra/">Markdown PHP Extra</a> syntax.</span>
		</p>

		<p>
			<button type="submit" name="submit" class="button primary">Save</button>
		</p>
	</form>	

</div>