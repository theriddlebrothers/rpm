<div id="document">

	<h2><?php echo $title; ?></h2>
	
	<form action="" method="POST">
	
		<p>
                       
			<label>Document <?php  if($template){ echo ' Template ';}; ?>Title</label><br />
                        
			<input type="text" class="text title" name="title" value="<?php echo postback($doc, 'title'); ?>" />
		</p>

                
                <?php if(!$template){ ?>
		<p>
                    
			<label>Document Date</label><br />
			<input id="docDate" name="doc_date" type="text" class="datepicker text" value="<?php if (postback($doc, 'doc_date')) echo date("m/d/Y", strtotime(postback($doc, 'doc_date'))); ?>" />
		</p>
                
		
		<?php echo dialog_select_project($doc->project); ?>
		
                <?php } //endif?>
                
		<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
		<input type="hidden" name="type" value="<?php echo $type; ?>" />
		
		<?php $this->load->view('cp/documents/forms/' . $type, array('fields'	=>	$fields)); ?>
		
		<p>
			<button type="submit" name="submit" class="button primary">Save</button>
		</p>
	</form>

</div>