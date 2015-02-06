<?php if ($items) : ?>

	<table border="1" cellpadding="3" cellspacing="0" width="100%">
		<thead>
			<tr bgcolor="#BFBFBF">
				<th class="id" align="left" width="10%">ID</th>
				<th class="title" align="left" width="20%">Function/Title</th>
				<th class="description" align="left">Description</th>
			</tr>
		</thead>
		<tbody>
			<?php $row = 1; ?>
			<?php foreach($items as $i) : ?>
				<tr <?php if (($row % 2) == 0) : ?>bgcolor="#EFEFEF"<?php endif; ?>>
					<td><?php echo $i['id']; ?></td>
					<td><?php echo $i['name']; ?></td>
					<td><?php echo markdownify($i['description']); ?></td>
				</tr>
			<?php $row++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif; ?>