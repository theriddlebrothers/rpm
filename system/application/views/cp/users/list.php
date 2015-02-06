<div id="listUsers">

	<h1>Users</h1>
			
	<div class="span-24 last">
		
		<?php if (user_can('create', 'users')) : ?>
			<p><a href="<?php echo site_url('users/create'); ?>">Create new</a></p>
		<?php endif; ?>
		
		<?php if ($users) : ?>
			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Username</th>
						<th>Email</th>
						<th>Role</th>
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
						<?php foreach($users as $user) : ?>
							<tr>
								<td><?php echo $user->name; ?></td>
								<td><?php echo $user->username; ?></td>
								<td><?php echo $user->email; ?></td>
								<td><?php echo ucfirst($user->role); ?></td>
								<td class="actions span-2">
									<!--<?php if (user_can('view', 'users')) : ?>
										<a class="icon view" href="<?php echo site_url('users/view/' . $user->id); ?>">View</a>
									<?php endif; ?>-->
									<?php if (user_can('edit', 'users')) : ?>
										<a class="icon edit" href="<?php echo site_url('users/edit/' . $user->id); ?>">Edit</a>
									<?php endif; ?>
									<?php if (user_can('delete', 'users')) : ?>
										<a class="icon delete popup" href="<?php echo site_url('users/delete/' . $user->id); ?>">Delete</a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
			</table>				
	
		<?php else : ?>
			<p>No users listed.</p>
		<?php endif; ?>

	</div>
	
</div>