<script>
	// we generate dropdown menus from the $tasks and $statuses tables
	
	var dropdown_group = function() { 
		return '<div class="controls">' +
	    	'<select class="task_id_new input-xlarge">' +
	    		<?php foreach ($tasks as $task): ?>
					'<option value="<?php echo $task['task_id'] ?>"><?php echo $task['task_name'] ?></option>' +
				<?php endforeach ?>
			'</select>' +
			'<select class="status_id_new input-xlarge">' +
		      	<?php foreach ($statuses as $status): ?>
					'<option value="<?php echo $status['status_id'] ?>"><?php echo $status['status_name'] ?></option>' +
				<?php endforeach ?>
		    '</select>' +
		    '<a class="btn remove-task">Remove Task</a>' +
		'</div>';
	};
	
	var multiselect_users = function(shot_task_id) {
		return '<select class="task_owners" name="task_owners[' + shot_task_id + '][]" class="input-xlarge" multiple="multiple">' +
		<?php foreach ($users as $user): ?>
			'<option value="<?php echo $user['id'] ?>">' +
				'<?php echo $user['first_name'] . ' ' . $user['last_name'] ?>' +
			'</option>' +
		<?php endforeach ?>
		'</select>';
	} ;
	
	$(document).ready(function() {
		// we do check against this variable to see if a task exists already
		var old_id = '';
				    
	    $(document).on("click", ".remove-task", function() {
	    	var task_id = $(this).prev().prev().val();
	    	var name_value = 'tasks[' + task_id + '][status_id]';
	    	$('#tasks-fields input[name="' + name_value + '"]').remove();
	    	console.log(task_id);
			$(this).parent().remove();
		});
			
		$(document).on("click", ".task_id", function() {
			old_id = $(this).val();
		});
		
		$(document).on("click", ".task_id_new option", function() {
			var shot_id = $('input[name=shot_id]').val();
			var task_id = $(this).val();
			var status_id = $(this).parent().next().val();
			var name_value = 'tasks[' + task_id + '][status_id]';
			
			if ($('#tasks-fields input[name="' + name_value + '"]').val() != null) {
				console.log('The field exists');
			} else {
				console.log('Adding new task');
				$('#tasks-fields').append('<input type="hidden" name="' + name_value + '" value="' + status_id + '">');
				
				var shot_task_id = '';
				
				var target = $(this).parent().next()[0];
				
				//var set_shot_task_id = function(shot_task_id) {
				//	$(this).parent().next().after(multiselect_users(shot_task_id));
				//}
				
				
				$.post("<?php echo site_url('/shots/post_add_shot_task/'); ?>" + "/" + shot_id, { 'task_id': task_id, 'status_id': status_id })
				.done(function(data) {
					shot_task_id = data;
					$(target).after(multiselect_users(shot_task_id));		
				});

				// we remove temporary classes and assign the normal one
				$(this).parent().removeClass('task_id_new').addClass('task_id');
				$(this).parent().next().removeClass('status_id_new').addClass('status_id');
			}

			//$(this).parent().before(aa); <input type="hidden" name="tasks[1][status_id]" value="7"> task_id status_id
			//tasks-fields
		});
		
		$(document).on("click", ".task_id option", function() {
			console.log(old_id);
			console.log($(this).parent().val());
			var task_id = $(this).val();
			var status_id = $(this).parent().next().val();
			var old_name_value = 'tasks[' + old_id + '][status_id]';
			var name_value = 'tasks[' + task_id + '][status_id]';
			
			if ($('#tasks-fields input[name="' + name_value + '"]').val() != null) {
				console.log('The field exists');
			} else {
				console.log('Adding new task');
				$('#tasks-fields input[name="' + old_name_value + '"]').remove();
				$('#tasks-fields').append('<input type="hidden" name="' + name_value + '" value="' + status_id + '">');
			}
			
			//$(this).parent().before(aa); <input type="hidden" name="tasks[1][status_id]" value="7"> task_id status_id
			//tasks-fields
		});
		
		$(document).on("click", ".status_id option", function() {
			var status_id = $(this).val()
			var task_id = $(this).parent().prev().val();
			console.log(task_id);
			$('input[name="tasks[' + task_id +'][status_id]"]').val(status_id);
			/*
			var name_value = 'tasks[' + task_id + '][status_id]';
			$('#tasks-fields').append(name_value);
			console.log($(this).parent().attr('name'));
			if ($('#tasks-fields input').attr('name') === name_value){
				console.log('This task exists already');
			} else {
				$('#tasks-fields').append('<input type="hidden" name="' + name_value + '" value="1">')
			}
			*/
			
			//$(this).parent().before(aa); <input type="hidden" name="tasks[1][status_id]" value="7"> task_id status_id
			//tasks-fields
		});
		
		$(".task_owners").chosen();
		
	});
</script>

<!-- Hidden inputs-->
<?php echo form_hidden('shot_id', $shot['shot_id']); ?>

<div class="row-fluid">	
	<!-- Text input-->
	<div class="control-group span4">
		<label class="control-label" for="shot_name">Name</label>
		<div class="controls">
			<input id="shot_name" name="shot_name" value="<?php echo $shot['shot_name']; ?>" class="input-xlarge" required="" type="text">
			<p class="help-block">Shot name, such as "a2s32"</p>
		</div>
		
		<!-- Select Scene -->
		<label class="control-label" for="scene_id">Scene</label>
		<div class="controls">
			<select id="scene_id" name="scene_id" class="input-xlarge">
		      	<?php foreach ($scenes as $scene): ?>
					<option value="<?php echo $scene['scene_id'] ?>"  <?php echo ($scene['scene_id'] == $shot['scene_id'] ? "selected=\"selected\"" : ""); ?>><?php echo $scene['scene_name'] ?></option>
				<?php endforeach ?>
		    </select>
	  	</div>
	</div>
	
	<!-- Textarea -->
	<div class="control-group span4">
	  	<label class="control-label" for="shot_description">Shot description</label>
	  	<div class="controls">                     
	    	<textarea id="shot_description" name="shot_description"><?php echo $shot['shot_description']; ?></textarea>
	  	</div>
	</div>
	
	<!-- Textarea -->
	<div class="control-group span4">
	  	<label class="control-label" for="shot_notes">Notes</label>
	  	<div class="controls">                     
	    	<textarea id="shot_notes" name="shot_notes"><?php echo $shot['shot_notes']; ?></textarea>
	  	</div>
	</div>
</div>


<!-- Select Status -->
<div class="control-group">
	<label class="control-label" for="status_id">Status</label>
	<div class="controls">
		<select id="status_id" name="status_id" class="input-xlarge">
	      	<?php foreach ($statuses as $status): ?>
				<option value="<?php echo $status['status_id'] ?>"  <?php echo ($status['status_id'] == $shot['status_id'] ? "selected=\"selected\"" : ""); ?>><?php echo $status['status_name'] ?></option>
			<?php endforeach ?>
	    </select> 
  	</div>
</div>

<!-- Select Task -->
<div class="control-group" id="tasks-selectors">
	<label class="control-label" for="stage_id">Task</label>
	
		<!-- <select id="task_id" name="task_id" class="input-xlarge">
	      	<?php foreach ($tasks as $task): ?>
				<option value="<?php echo $task['task_id'] ?>"><?php echo $task['task_name'] ?></option>
			<?php endforeach ?>
	    </select> -->
	    
    <?php foreach ($shot_tasks as $shot_task): ?>

    <div class="controls">
    	<select class="task_id" name="task_id" class="input-xlarge">
    		<?php foreach ($tasks as $task): ?>
				<option value="<?php echo $task['task_id'] ?>" <?php echo ($task['task_id'] == $shot_task['task_id'] ? "selected=\"selected\"" : ""); ?>><?php echo $task['task_name'] ?></option>
			<?php endforeach ?>
		</select>
		<select class="status_id" name="status_id" class="input-xlarge">
	      	<?php foreach ($statuses as $status): ?>
				<option value="<?php echo $status['status_id'] ?>"  <?php echo ($status['status_id'] == $shot_task['status_id'] ? "selected=\"selected\"" : ""); ?>><?php echo $status['status_name'] ?></option>
			<?php endforeach ?>
	    </select>

	   <select class="task_owners" name="task_owners[<?php echo $shot_task['shot_task_id'] ?>][]" class="input-xlarge" multiple="multiple">
	   <?php 
	   $shot_tasks_users_id = array();
	   foreach ($shot_tasks_users as $shot_task_user)
	   {
	    	// Selector for owners of each task. First we build an array with the user_ids associated
	    	// with a specific task	
			
    		if ($shot_task_user['shot_task_id'] == $shot_task['shot_task_id'] ) 
    		{
    			array_push($shot_tasks_users_id, $shot_task_user['user_id']);
    		}
    	}
    	?>
					
		<?php foreach ($users as $user): ?>
			<option value="<?php echo $user['id'] ?>" <?php echo (in_array($user['id'], $shot_tasks_users_id) ? "selected=\"selected\"" : ""); ?>>
				<?php echo $user['first_name'] . ' ' . $user['last_name'] ?>
			</option>
		<?php endforeach ?>
		</select>
	    
	    <a class="btn remove-task">Remove Task</a>
	</div>
	<?php endforeach ?>
	<div class="controls">
		<a class="btn add-task">Add Task</a>
	</div>

	

  	
  	<!-- <select id="tasks" name="tasks" class="input-xlarge" multiple="multiple">
  	<?php foreach ($shot_tasks as $task): ?>
		<option value="<?php echo $task['task_id'] ?>"><?php echo $task['task_name'] ?></option>
	<?php endforeach ?>
	</select> -->
	
	<div id="tasks-fields">
		<?php foreach ($shot_tasks as $shot_task): ?>
			<input type="hidden" name="tasks[<?php echo $shot_task['task_id'] ?>][status_id]" value="<?php echo $shot_task['status_id'] ?>">
		<?php endforeach ?>
	</div>
</div>


<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="shot_duration">Shot duration</label>
  <div class="controls">
    <input id="shot_duration" name="shot_duration" value="<?php echo $shot['shot_duration']; ?>" class="input-xlarge" required="" type="text">
    <p class="help-block">Duration of the shot in frames</p>
  </div>
</div>


<!-- Button -->
<div class="control-group">
	<label class="control-label" for="submit">Submit</label>
	<div class="controls">
		<a href="#" class="btn btn-inverse edit-shot-submit">Edit Shot</a>
		<a href="#" class="btn edit-shot-cancel">Cancel</a>
		<a href="<?php echo site_url('/shots/delete/' . $shot['shot_id']); ?>" id="submit" name="submit" class="btn btn-danger">Delete Shot</a>
	</div>
</div>

