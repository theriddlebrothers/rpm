<div id="viewProject">

	<form>
		
		<h1><?php echo $project->name; ?> <span id="projectNumber">(<?php echo $project->fullProjectNumber(); ?>)</span></h1>
			
		<?php if (user_can('edit', 'projects')) : ?>
			<p class="actions">
				<a href="<?php echo site_url('projects/edit/' . $project->id); ?>">Edit</a>
			</p>
		<?php endif; ?>
		
		<div id="projectHeader" class="span-24 last">
			<div class="span-12">
				<fieldset>
					<legend>Project Information</legend>
					
					<table>
						<?php if ($project->parentproject->exists()) : ?>
						<tr>
							<th>Parent Project</th>
							<td><a href="<?php echo site_url('projects/view/' . $project->parentproject->id); ?>"><?php echo $project->parentproject->name; ?></td>
						</tr>
						<?php endif; ?>
						<tr>
							<th>Company</th>
							<td><?php echo $project->company->name; ?></td>
						</tr>
						<tr>
							<th>Status</th>
							<td><?php echo $project->company->name; ?></td>
						</tr>
						<tr>
							<th>Target Start Date</th>
							<td>
								<?php if ($project->start_date) echo date("m/d/Y", strtotime($project->start_date)); ?>
							</td>
						</tr>
						<tr>
							<th>Target End Date</th>
							<td>
								<?php if ($project->end_date) echo date("m/d/Y", strtotime($project->end_date)); ?>
							</td>
						</tr>
						<?php if (user_can('view', 'svn')) : ?>
							<tr>
								<th>SVN Repository</th>
								<td>
									<?php if ($project->svn_repo) : ?>
										<a href="<?php echo site_url('svn/browse/' . $project->svn_repo); ?>">Browse Repository</a>
									<?php else : ?>
										None listed.
									<?php endif; ?>
								</td>
							</tr>
						<?php endif; ?>
					</table>
					
				</fieldset>
			</div>
			<div class="span-12 last">
				<fieldset>
					<legend>Project Contacts</legend>
					<?php if ($project->contact->all) : ?>
						<table>
							<thead>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Email</th>
								<th></th>
							</thead>
						<?php foreach($project->contact->all as $contact) : ?>
							<tr>
								<td><?php echo $contact->first_name; ?></td>
								<td><?php echo $contact->last_name; ?></td>
								<td><?php echo $contact->email; ?></td>
								<th>
									<?php if (user_can('view', 'contacts')) : ?>
										<a class="icon view" href="<?php echo site_url('contacts/view/' . $contact->id); ?>">View</a>
									<?php endif; ?>
								</th>
							</tr>
						<?php endforeach; ?>
						</table>
					<?php else : ?>
						<p>No contacts listed.</p>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
		
		<div class="span-24 last">
			<div id="project-tabs" class="tabs">
				<ul>
					<li><a href="#tab-description">Description</a></li>
					
					<?php if (user_can('view', 'documents')) : ?>
						<li><a href="#tab-documents">Documents</a></li>
					<?php endif; ?>
					
					<?php if (user_can('view', 'files')) : ?>
						<li><a href="#tab-files">Files</a></li>
					<?php endif; ?>
					
					<?php if (user_can('view', 'issues')) : ?>
						<li><a href="#tab-issues">Issues</a></li>
					<?php endif; ?>
					
					<?php if (user_can('view', 'statusreports')) : ?>
						<li><a href="#tab-status-reports">Status Reports</a></li>
					<?php endif; ?>
					
					<?php if (user_can('view', 'invoices')) : ?>
						<li><a href="#tab-invoices">Invoices</a></li>
					<?php endif; ?>
					
					<?php if (user_can('view', 'estimates')) : ?>
						<li><a href="#tab-estimates">Estimates</a></li>
					<?php endif; ?>
					
					
					<?php if (user_can('view', 'tasks')) : ?>
						<li><a href="#tab-tasks">Tasks</a></li>
					<?php endif; ?>
					
					<?php if (user_can('view', 'timelogs')) : ?>
						<li><a href="#tab-timelogs">Timelogs</a></li>
					<?php endif; ?>
					
					
				</ul>
				
				<div id="tab-description">
					<?php if ($project->description) : ?>
						<?php echo $project->description; ?>
					<?php else : ?>
						No description listed.					
					<?php endif; ?>
				</div>
				
				
				<?php $this->load->view('cp/projects/tabs/documents'); ?>
				<?php $this->load->view('cp/projects/tabs/files'); ?>
				<?php $this->load->view('cp/projects/tabs/issues'); ?>
				<?php $this->load->view('cp/projects/tabs/status-reports'); ?>
				<?php $this->load->view('cp/projects/tabs/invoices'); ?>
				<?php $this->load->view('cp/projects/tabs/estimates'); ?>
				<?php $this->load->view('cp/projects/tabs/tasks'); ?>
				<?php $this->load->view('cp/projects/tabs/timelogs'); ?>
					
			
			</div>
		</div>
		
	</form>
	
</div>

<script type="text/javascript">
	$(function() {
	
		// Tabs
		$( ".tabs" ).tabs({
			cookie: {
				// store cookie for a day, without, it would be a session cookie
				expires: 1
			}
		});

	});
</script>