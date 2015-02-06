<div class="span-6">
	<ul>
		<li>
			People &amp; Places
			<ul>
				<?php if (user_can('view', 'activities')) : ?>
					<li><a href="<?php echo site_url('activities'); ?>">Activities</a></li>
				<?php endif; ?>
				<?php if (user_can('view', 'companies')) : ?>
					<li><a href="<?php echo site_url('companies'); ?>">Companies</a></li>
				<?php endif; ?>
				<?php if (user_can('view', 'contacts')) : ?>
					<li><a href="<?php echo site_url('contacts'); ?>">Contacts</a></li>
				<?php endif; ?>
				<?php if (user_can('view', 'wiki')) : ?>
					<li><a href="<?php echo site_url('wiki'); ?>">Wiki</a></li>
				<?php endif; ?>
			</ul>
		</li>
	</ul>
</div>

<div class="span-6">
	<ul>
		<li>
			Projects
			<ul>
                    <?php if (user_can('view', 'documents')) : ?>
                            <li><a href="<?php echo site_url('documents'); ?>">Documents</a></li>
                    <?php endif; ?>
                    <?php if (user_can('view', 'estimates')) : ?>
                            <li><a href="<?php echo site_url('estimates'); ?>">Estimates</a></li>
                    <?php endif; ?>
                    <?php if (user_can('view', 'issues')) : ?>
                            <li><a href="<?php echo site_url('issues'); ?>">Issues</a></li>
                    <?php endif; ?>
                    <?php if (user_can('view', 'projects')) : ?>
                            <li><a href="<?php echo site_url('projects'); ?>">Projects</a></li>
                    <?php endif; ?>
                    <?php if (user_can('view', 'tasks')) : ?>
                            <li><a href="<?php echo site_url('tasks'); ?>">Tasks</a></li>
                    <?php endif; ?>
                    <?php if (user_can('view', 'timelogs')) : ?>
                            <li><a href="<?php echo site_url('timelogs'); ?>">Time Logs</a></li>
                    <?php endif; ?>
			</ul>
		</li>
	</ul>
</div>


<div class="span-6">
	<ul>
		<li>
			Accounting &amp; Reports
			<ul>
                <?php if (user_can('view', 'retainers')) : ?>
                        <li><a href="<?php echo site_url('retainers'); ?>">Retainers</a></li>
                <?php endif; ?>
                <?php if (user_can('view', 'invoices')) : ?>
                        <li><a href="<?php echo site_url('invoices'); ?>">Invoices</a></li>
                <?php endif; ?>
                <?php if (user_can('view', 'reports')) : ?>
                        <li><a href="<?php echo site_url('reports'); ?>">Reports</a></li>
                <?php endif; ?>
                <?php if (user_can('view', 'statusreports')) : ?>
                        <li><a href="<?php echo site_url('statusreports'); ?>">Status Reports</a></li>
              	<?php endif; ?>
			</ul>
		</li>
	</ul>
</div>

<div class="span-6 last">
	<ul>
		<li>
			Account
			<ul>
				<?php if (in_role(ROLE_ADMINISTRATOR)) : ?>
					<li><a href="<?php echo site_url('admin'); ?>">Admin</a></li>
				<?php endif; ?>
				<li><a href="<?php echo site_url('/welcome/index/?logout=true'); ?>">Logout</a></li>
			</ul>
		</li>
	</ul>
</div>