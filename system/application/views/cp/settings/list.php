<div id="listSettings">

	<h1>Settings</h1>
			
	<div class="span-24 last">
	
		<?php if ($settings) : ?>
			<table>
				<thead>
					<tr>
						<th>Setting Name</th>
						<th>Value</th>
						<th></th>
					</tr>
				</thead>	
				<tfoot>
					<tr>
						<td colspan="3">
							<?php echo $pager; ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
						<?php foreach($settings as $setting) : ?>
							<tr>
								<td><?php echo $setting->name; ?></td>
								<td><?php echo $setting->value; ?></td>
								<td class="actions span-2">
									<?php if (user_can('edit', 'settings')) : ?>
										<a class="icon edit" href="<?php echo site_url('settings/edit/' . $setting->id); ?>">Edit</a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
		
			<p>No projects listed.</p>
			
		<?php endif; ?>

	</div>
	
</div>