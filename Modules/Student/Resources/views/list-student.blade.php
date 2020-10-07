@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Student Manager</b></h4>			
	@include('student::tabs')	
		<div class="box"> 
			<div class="box-body">
				<div class="row">				
					@include('backend.partials.course-type-course-partial')					
					<input type="hidden" name="" id="selected_course_id" value="{{$selected_course_id}}">	
					<input type="hidden" name="" id="selected_course_type_id" value="{{$selected_course_type_id}}">
				</div>
				<div class="table-responsive">
					@if(count($students))
						<?php  $i =1; ?>						
			        <table class="table table-bordered table-hover" id="normal-table">        
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
      							<i class="glyphicon glyphicon-file"></i> 
      							</a> 			
								@include('student::modal.student-view-modal')	

								<a href = "{{route('edit-student', $student->id)}}"><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> <i class="fa fa-fw fa-edit"></i></button></a>		                       
		                        <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#confirmDelete{{$student->id}}" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
      							 <i class="glyphicon glyphicon-trash"></i> 
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
				</div>		
			</div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')

<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-course-function.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>

	const config = {
        routes: {
            updateCourseRoute: '{{route("get-courses-from-course-type")}}',                        
        }
    };

    $(document).ready( function () {    	

    	$('.select2').select2();

    	$('#normal-table').DataTable();

    	$('#course_type_id').on('change', function() {
    		
            updateCourse($('#course_type_id').val());
        });

        $('#course_id').on('change', function() {
        	
        	const course_type_id = $('#course_type_id').val();
        	const course_id = $('#course_id').val();
        	updateStudentList(course_type_id, course_id);
        });

        function updateStudentList(course_type_id, course_id) 
        {
        	$.ajax({
        		type: 'GET', 
        		url: '{{route("get-student-from-course-type-and-course")}}', 
        		data: {
        			course_type_id, 
        			course_id, 

        		}, 
        		success:function(data)
        		{
        			$('.table-responsive').html(data);
        			$('#ajax-table').DataTable();
        		}, 
        		error: function (jqxhr, textStatus)
        		{
        			toastr.error("Request Error" + textStatus);
        		}
        	})
        }
	});
</script>
@endsection

