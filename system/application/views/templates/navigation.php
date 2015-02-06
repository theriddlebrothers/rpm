

<?php if (user_can('view', 'companies')) : ?>
	<li><a href="<?php echo site_url('companies'); ?>">Companies</a></li>
<?php endif; ?>

<?php if (user_can('view', 'contacts')) : ?>
	<li><a href="<?php echo site_url('contacts'); ?>">Contacts</a></li>
<?php endif; ?>

<?php if (user_can('view', 'projects')) : ?>
	<li><a href="<?php echo site_url('projects'); ?>">Projects</a></li>
<?php endif; ?>

<?php if (user_can('view', 'estimates')) : ?>
	<li><a href="<?php echo site_url('estimates'); ?>">Estimates</a></li>
<?php endif; ?>

<?php if (user_can('view', 'invoices')) : ?>
	<li><a href="<?php echo site_url('invoices'); ?>">Invoices</a></li>
<?php endif; ?>

<?php if (user_can('view', 'reports')) : ?>
	<li><a href="<?php echo site_url('reports'); ?>">Reports</a></li>
<?php endif; ?>

<?php if (user_can('edit', 'users')) : ?>
	<li><a href="<?php echo site_url('admin'); ?>">Admin</a></li>
<?php endif; ?>


<?php if (!in_role(ROLE_EMPLOYEE)) : ?>
	<li><input class="text" id="query" type="text" name="q" value="Search..." /></li>
<?php endif; ?>
