@if(count($students))
	<?php  $i =1; ?>						
<table class="table table-bordered table-hover" id="ajax-table">        
	<thead>
		<tr style="background-color:#333; color:white;">
		<th>SN</th>
		<th>Student ID</th>							
		<th>Student Name</th>
		<th>Email</th>
		<th>Phone</th>
		<th>Action</th>
		</tr>
	</thead>
	<tbody>
	@foreach($students as $index => $student)						
		<tr>
		<td>{{$i++}}</td>
		<td>{{$student->student_id}}</td>
		<td>{{$student->name }}</td>
		<td>{{$student->email }}</td>
		<td>{{$student->phone }}</td>										
		<td>
			
			<a class="btn btn-success btn-flat"  data-toggle="modal" data-target="#viewStudent{{$student->id}}" data-title="View Student" data-message="View Student">
				View <i class="glyphicon glyphicon-file"></i> 
				</a> 			
			@include('student::modal.student-view-modal')	

			<a href = "{{route('edit-student', $student->id)}}"><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> Edit <i class="fa fa-fw fa-edit"></i></button></a>		                       
            <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#confirmDelete{{$student->id}}" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
				Remove <i class="glyphicon glyphicon-trash"></i> 
				</a> 			
			@include('student::modal.student-delete-modal')									 
		</td>
		</tr>
	@endforeach
	</tbody>       								
</table>

@else
<div class="alert alert-warning alert-dismissable">
		<h4><i class="icon fa fa-warning"></i>NO STUDENTS AVAILABLE</h4>
	</div>	
@endif	