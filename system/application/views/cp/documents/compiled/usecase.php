<table id="usecase" border="1" cellpadding="3" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th align="left" width="25%">Use Case </th>
			<th align="left" colspan="3">UC-<?php echo $item->getUCID(); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th align="left" >Use Case Name</th>
			<td colspan="3"><?php echo $item->name; ?></td>
		</tr>
		<tr>
			<th align="left" >Date Created</th>
			<td><?php echo date("m/d/Y", strtotime($item->date_created)); ?></td>
			<th align="left" >Date Last Updated</th>
			<td><?php echo date("m/d/Y", strtotime($item->date_updated)); ?></td>
		</tr>
		<tr>
			<th align="left" >Actors</th>
			<td colspan="3"><?php echo $item->actors; ?></td>
		</tr>
		<tr>
			<th align="left" >Description</th>
			<td colspan="3"><?php echo markdownify($item->description); ?></td>
		</tr>
		<tr>
			<th align="left" >Trigger</th>
			<td colspan="3"><?php echo markdownify($item->trigger); ?></td>
		</tr>
		<tr>
			<th align="left" >Preconditions</th>
			<td colspan="3"><?php echo markdownify($item->preconditions); ?></td>
		</tr>
		<tr>
			<th align="left" >Postconditions</th>
			<td colspan="3"><?php echo markdownify($item->postconditions); ?></td>
		</tr>
		<tr>
			<th align="left" >Normal Flow</th>
			<td colspan="3"><?php echo markdownify($item->normal_flow); ?></td>
		</tr>
		<tr>
			<th align="left" >Alternative Flow</th>
			<td colspan="3"><?php echo markdownify($item->alternative_flow); ?></td>
		</tr>
		<tr>
			<th align="left" >Exceptions</th>
			<td colspan="3"><?php echo markdownify($item->exceptions); ?></td>
		</tr>
		<tr>
			<th align="left" >Includes</th>
			<td colspan="3"><?php echo markdownify($item->includes); ?></td>
		</tr>
		<tr>
			<th align="left" >Frequency of Use</th>
			<td colspan="3"><?php echo markdownify($item->frequency); ?></td>
		</tr>
		<tr>
			<th align="left" >Business Rules</th>
			<td colspan="3"><?php echo markdownify($item->business_rules); ?></td>
		</tr>
		<tr>
			<th align="left" >Special Requirements</th>
			<td colspan="3"><?php echo markdownify($item->special_requirements); ?></td>
		</tr>
		<tr>
			<th align="left" >Assumptions</th>
			<td colspan="3"><?php echo markdownify($item->assumptions); ?></td>
		</tr>
		<tr>
			<th align="left" >Notes and Issues</th>
			<td colspan="3"><?php echo markdownify($item->notes); ?></td>
		</tr>
	</tbody>
</table>