<p class="actions" class="printhide">
	<?php if (user_can('create', 'estimates')) : ?>
		<a href="<?php echo site_url('estimates/create'); ?>">Create new</a>
	<?php endif; ?>
	
	<a id="showFilter" href="#">Filter Results</a>
</p>


<div id="filters" class="span-17" style="display:none;">

	<fieldset>
	
		<legend>Filter</legend>

		<div class="span-5">
			<p>
				<label>Status</label><br />
				<select name="status">
					<option value="">Select a status...</option>
					<option <?php if ($this->input->get('status') == 'Approved') echo ' selected="selected"'; ?> value="Approved">Approved</option>
					<option <?php if ($this->input->get('status') == 'Pending Approval') echo ' selected="selected"'; ?> value="Pending Approval">Pending Approval</option>
					<option <?php if ($this->input->get('status') == 'Rejected') echo ' selected="selected"'; ?> value="Rejected">Rejected</option>
				</select>
			</p>
		</div>
		
		
		<div class="span-9">							
			<?php dialog_select_company($company); ?>
		</div>
	
		<button id="apply-ftilers" type="submit" class="button button-small" name="filter">Filter</button>
		<input type="hidden" name="search" value="1" />
	
	</fieldset>
			
</div>