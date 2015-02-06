<div id="listEstimates">

	<h1>Estimates</h1>
			
        <form action="<?php echo site_url('cp/estimates/index'); ?>/" method="get">

            <div class="span-24 last">

                    <?php $this->load->view('cp/estimates/filter'); ?>

                    <?php if ($estimates) : ?>
                            <table>
                                    <thead>
                                            <tr>
                                                    <th>Number</th>
                                                    <th>Estimate Name</th>
                                                    <th class="money">Amount</th>
                                                    <th></th>
                                            </tr>
                                    </thead>	
                                    <tfoot>
                                            <tr>
                                                    <td colspan="4">
                                                            <?php echo $pager; ?>
                                                    </td>
                                            </tr>
                                    </tfoot>
                                    <tbody>
                                                    <?php foreach($estimates as $estimate) : ?>
                                                            <tr>
                                                                    <td><?php echo $estimate->estimate_number; ?></td>
                                                                    <td><?php echo $estimate->name; ?></td>
                                                                    <td class="money">$<?php echo number_format($estimate->getTotalEstimated(), 2); ?></td>
                                                                    <td class="actions span-2">
                                                                            <?php if (user_can('view', 'estimates')) : ?>
                                                                                    <a class="icon view" href="<?php echo site_url('estimates/view/' . $estimate->id); ?>">View</a>
                                                                            <?php endif; ?>
                                                                            <?php if (user_can('edit', 'estimates')) : ?>
                                                                                    <a class="icon edit" href="<?php echo site_url('estimates/edit/' . $estimate->id); ?>">Edit</a>
                                                                            <?php endif; ?> 
                                                                            <?php if(user_can('delete', 'estimates')) : ?>
                                                                                    <a class="icon delete popup" href="<?php echo site_url('estimates/delete/' . $estimate->id); ?>">Delete</a>
                                                                            <?php endif; ?>
                                                                    </td>
                                                            </tr>
                                                    <?php endforeach; ?>
                                    </tbody>
                            </table>				

                    <?php else : ?>
                            <p>No estimates listed.</p>
                    <?php endif; ?>

            </div>
            
        </form>
</div>


<script type="text/javascript">
	
	$(function() {
	
		$('#showFilter').click(function () {
			$('#filters').slideDown();
			return false;
		});
	

		$('#company').autocomplete('/cp/companies/ajax/search');
		
	});
	
</script>