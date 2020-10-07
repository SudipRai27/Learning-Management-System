@if(count($assigned_teachers))
<table class="table table-bordered table-hover" id="assigned-teacher-from-session-and-teacher-id">  
	<thead>
		<tr style="background-color:#333; color:white;">
		<th>SN</th>
		<th>Subject </th>							
		<th>Course Type </th>
		<th>Course</th>
		<th>Assigned In</th>
		<th>Assigned As</th>
		<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 1;
	?>
	@foreach($assigned_teachers as $index => $data)
		<tr>
		<td>{{$i++}}</td>
		<td>{{$data->subject_name}}</td>
		<td>{{$data->course_type }}</td>
		<td>{{$data->course_title }}</td>
		<td>{{$data->type }}</td>	
		<td>
			@if($data->type == "lecture")
				lecturer
			@else
				tutor
			@endif
		</td>										
		<td>		
			<a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteAssignedTeacher{{$data->id}}" data-title="Delete Lecturer" data-message="Are you sure you want to delete this lecturer ?">
      	      <i class="glyphicon glyphicon-trash"></i> 
            </a>                      
            @include('teacher::modal.delete-assigned-teacher-modal')      	
           
		</td>
		</tr>
	@endforeach
	</tbody>       								
</table>			        
@else
<div class="alert alert-warning alert-dismissable">
	<h4><i class="icon fa fa-warning"></i>Not assigned for this session</h4>
</div>	
@endif
