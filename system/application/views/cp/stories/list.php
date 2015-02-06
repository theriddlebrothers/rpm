<div id="listStories">

	<h1><?php echo $project->name; ?>: User Stories</h1>
			
	<?php if (user_can('view', 'stories')) : ?>
		<p><a href="<?php echo site_url('stories/view/' . $project->id); ?>">View</a></p>
	<?php endif; ?>
	
	<div class="span-24 last">
		
		<p>User stories should be in the format of "As a {user type} I want to {action} so that {result}".</p>
		
		<div id="message" style="display:none;" class="notice"></div>
		
		<ul id="stories" class="span-22 last">
		</ul>
		
		<div class="span-18 last actions">
			<p><button id="save" class="button primary"><img src="/images/icons/accept.png" alt="" /> Save</button></p>
		</div>
	</div>
	
</div>

<script id="storyTemplate" type="text/x-jquery-tmpl">
    <li class="span-20 last draggable">
    	<span class="span-11 description">
    		<label>Description</label>
    		<input type="hidden" name="id[]" value="${id}" />
    		<input type="hidden" name="delete[]" value="0" />
    		<input type="text" name="description[]" value="${description}" />
    	</span> 
    	
    	<span class="span-3 status">
    		<label>Status</label>
    		<select name="status[]">
    			{{each statusValues}}
    				<option {{if status == $value}}selected="selected"{{/if}}
    					value="${$value}">${$value}</option>
    			{{/each}}
    		</select>
    	</span>
    	
    	<span class="span-3 priority">
    		<label>Priority</label>
	    	<select name="priority[]">
	    		{{each priorityValues}}
	    			<option {{if priority == $value}}selected="selected"{{/if}}
	    				value="${$value}">${$value}</option>
	    		{{/each}}
	    	</select>
    	</span>
    	
    	<span class="span-2 effort">
    		<label>Effort</label>
    		<input type="text" name="effort[]" value="${effort}" />
    	</span>
    	
    	<span class="span-1 last">
    		<a href="#${id}" class="icon delete" />
    		<a href="#${id}" class="icon note" />
    	</span>
    	
   		<span class="span-19 last notes">
   			<label>Notes</label>
   			<textarea name="notes[]">${notes}</textarea>
   		</span>
   	</li>
</script>

<script type="text/javascript">

	var storiesList = $('#stories');
	
	var priorityValues = ["Low", "Medium", "High"];
	var statusValues = ["Future", "In Progress", "Complete"];
	
	var Stories = {
	
		newStory : function(description, status, priority, effort, notes) {
			if (description == undefined) description = "";
			if (status == undefined) status = "Future";
			if (priority == undefined) priority = "Low";
			if (effort == undefined) effort = "";
			if (notes == undefined) notes = "";
			
			return {
				description : description,
				status : status,
				priority : priority,
				effort : effort,
				notes : notes
			}
		},
	
		Interface : {
		
			add : function(story) {
				$('#storyTemplate').tmpl(story).appendTo(storiesList);
				$('textarea[name=notes[]]', storiesList).each(function() {
					if ($(this).val() == '') {
						$(this).parent().hide();
					}
				});
			},
			
			delete : function(el) {
				if ($(el).parent().parent().find('input[name=id[]]').val() == '') {
					// Item has not been stored yet, simply remove from DOM
					$(el).parent().parent().slideUp(function() {
						$(this).remove();
					});
				} else {
					// Mark for deletion
					$(el).parent().parent().find('input[name=delete[]]').val('1');
					$(el).parent().parent().slideUp();
				}
			}
			
		}
			
	};


	$(function() {
		
		/**
		 * Load initial data
		 */
		$.getJSON('<?php echo site_url('stories/ajax/get/'); ?>/', { project : <?php echo $project->id; ?> }, function(data) {
			Stories.Interface.add(data);
			
			// Create initial blank line
			Stories.Interface.add(Stories.newStory());
			
			// Drag and drop
			storiesList.sortable({
				items: 'li:not(:last-child)'
			});
		});
		
		/**
		 * Save stories
		 */
		$('#save').click(function() {	
			var ids = new Array();
			$('input[name=id[]]', storiesList).each(function() {
				ids[ids.length] = $(this).val();
			});
			
			var del = new Array();
			$('input[name=delete[]]', storiesList).each(function() {
				del[del.length] = $(this).val();
			});
			
			var descriptions = new Array();	
			$('input[name=description[]]', storiesList).each(function() {
				descriptions[descriptions.length] = $(this).val();
			});
			
			var statuses = new Array();	
			$('select[name=status[]]', storiesList).each(function() {
				statuses[statuses.length] = $(this).val();
			});
			
			var priorities = new Array();	
			$('select[name=priority[]]', storiesList).each(function() {
				priorities[priorities.length] = $(this).val();
			});
			
			var efforts = new Array();	
			$('input[name=effort[]]', storiesList).each(function() {
				efforts[efforts.length] = $(this).val();
			});	
			
			var notes = new Array();	
			$('textarea[name=notes[]]', storiesList).each(function() {
				notes[notes.length] = $(this).val();
			});	
					
					
					console.log(ids);
					
			$.post('<?php echo site_url('stories/ajax/save/'); ?>', { 
				project : <?php echo $project->id; ?>,
				ids: ids,
				descriptions : descriptions,
				statuses : statuses,
				priorities : priorities,
				efforts : efforts,
				notes : notes,
				'delete' : del
				}, function(data) {
					if (data != null) {
						$('tr input[name=id[]]', storiesList).each(function(index) {
							id = data[index];
							$(this).val(id);
						});
					}
					$('#message').html('User stories have been saved.').fadeIn();
				}, 'json'
			);
		});
		
		/**
		 * Add new row if last row has been filled
		 */
		$('input, select', storiesList).live('blur', function() {
			// if this was the last user story item, create a new item automatically
			// so users can simply tab through when adding stories
			var description = $('li:last-child input[name=description[]]');
			if (description.val() != '') {
				var newStory = { };
				Stories.Interface.add(Stories.newStory());
			}
		});
		 
		 /**
		  * Delete Story
		  */
		$('.delete', storiesList).live('click', function() {
			var a = $(this);
			var id = a.attr("href").replace("#", "");
			if (!a.parent().parent().is(':last-child')) {
				Stories.Interface.delete(a);
			}
			return false;
		});
		
		/**
		 * Show Notes
		 */
		$('.note', storiesList).live('click', function() {
			var noteEl = $(this).parent().parent().find('.notes');
			
			if (noteEl.is(':visible')) noteEl.hide();
			else noteEl.show();
			
			return false;
		});
		
	});
</script>