<!-- begin select user -->
<p>
	<label>User</label><br />
	<input name="user_name" class="text select-user" type="text" value="<?php echo $user->name; ?>" /> 
	<a href="#" class="icon inline find select-user">Find</a>
	<input type="hidden" name="user" value="<?php echo $user->id; ?>" />
</p>

<div style="display:none;">
	<div class="user-list dialog" title="Select a User">
		<?php $this->load->view('cp/dialogs/dialog-header', array('id'=>'search-user')); ?>
		<?php if ($users) : ?>
			<div class="list-wrap">
				<table>
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
						</tr>
					</thead>
					<?php foreach($users as $u) : ?>
						<tr>
							<td><a class="select" title="<?php echo $u->name; ?>" href="#<?php echo $u->id; ?>"><?php echo $u->name; ?></a></td>
							<td><?php echo $u->email; ?></td>
						</tr>
					<?php endforeach; ?>	
				</table>	
			</div>
		<?php else : ?>
			<p>No users found.</p>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		// display dialog
		$('.select-user').click(function() {
			$('.user-list').dialog( {
					buttons: {
						"Cancel" : function() {
							$(this).dialog("close"); 
						}
				},
				modal : true,
				resizable: false,
				width : '500px'
			});
			return false;
		});
			
		// selection of user
		$('.select', '.user-list').click(function() {
			var id = $(this).attr("href").replace("#", "");
			var name = $(this).attr("title");
			$('input[name="user_name"]').val(name);
			$('input[name="user"]').val(id);
			$('.user-list').dialog("close");
			return false;
		});
		
		// prevent manual user input
		$('.select-user').focus(function(){
		    this.blur();
		});
	});
</script>
<!-- end select user -->