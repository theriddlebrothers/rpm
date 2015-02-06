<div id="document">

	<h1><?php echo $doc->title; ?></h1>
	
	<form action="" method="post">
		<?php $this->load->view('cp/documents/section'); ?>
		
		<table id="usecase" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th>Use Case Name</th>
				<td colspan="3"><input type="text" name="name" value="<?php echo postback($section, 'docusecase', 'name'); ?>" class="text" /></td>
			</tr>
			<tr>
				<th>Date Created:</th>
				<td><?php if ($section->docusecase->date_created) date("m/d/Y", strtotime($section->docusecase->date_created)); ?></td>
				<th>Date Last Updated:</th>
				<td><?php if ($section->docusecase->date_updated) date("m/d/Y", strtotime($section->docusecase->date_updated)); ?></td>
			</tr>
			<tr>
				<th>Actors</th>
				<td colspan="3"><input type="text" name="actors" value="<?php echo postback($section, 'docusecase', 'actors'); ?>" class="text" /></td>
			</tr>
			<tr>
				<th>Description</th>
				<td colspan="3"><textarea class="text" name="description"><?php echo postback($section, 'docusecase', 'description'); ?></textarea></td>
			</tr>
			<tr>
				<th>Trigger</th>
				<td colspan="3"><textarea class="text" name="trigger"><?php echo postback($section, 'docusecase', 'trigger'); ?></textarea></td>
			</tr>
			<tr>
				<th>Preconditions</th>
				<td colspan="3"><textarea class="text" name="preconditions"><?php echo postback($section, 'docusecase', 'preconditions'); ?></textarea></td>
			</tr>
			<tr>
				<th>Postconditions</th>
				<td colspan="3"><textarea class="text" name="postconditions"><?php echo postback($section, 'docusecase', 'postconditions'); ?></textarea></td>
			</tr>
			<tr>
				<th>Normal Flow</th>
				<td colspan="3"><textarea class="text" name="normal_flow"><?php echo postback($section, 'docusecase', 'normal_flow'); ?></textarea></td>
			</tr>
			<tr>
				<th>Alternative Flow</th>
				<td colspan="3"><textarea class="text" name="alternative_flow"><?php echo postback($section, 'docusecase', 'alternative_flow'); ?></textarea></td>
			</tr>
			<tr>
				<th>Exceptions</th>
				<td colspan="3"><textarea class="text" name="exceptions"><?php echo postback($section, 'docusecase', 'exceptions'); ?></textarea></td>
			</tr>
			<tr>
				<th>Includes</th>
				<td colspan="3"><textarea class="text" name="includes"><?php echo postback($section, 'docusecase', 'includes'); ?></textarea></td>
			</tr>
			<tr>
				<th>Frequency of Use</th>
				<td colspan="3"><textarea class="text" name="frequency"><?php echo postback($section, 'docusecase', 'frequency'); ?></textarea></td>
			</tr>
			<tr>
				<th>Business Rules</th>
				<td colspan="3"><textarea class="text" name="business_rules"><?php echo postback($section, 'docusecase', 'business_rules'); ?></textarea></td>
			</tr>
			<tr>
				<th>Special Requirements</th>
				<td colspan="3"><textarea class="text" name="special_requirements"><?php echo postback($section, 'docusecase', 'special_requirements'); ?></textarea></td>
			</tr>
			<tr>
				<th>Assumptions</th>
				<td colspan="3"><textarea class="text" name="assumptions"><?php echo postback($section, 'docusecase', 'assumptions'); ?></textarea></td>
			</tr>
			<tr>
				<th>Notes and Issues</th>
				<td colspan="3"><textarea class="text" name="notes"><?php echo postback($section, 'docusecase', 'notes'); ?></textarea></td>
			</tr>
		</table>
		
		<p>
			<button type="submit" name="submit" class="button primary">Save</button>
		</p>
	</form>
</div>