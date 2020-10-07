@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Teacher Manager</b></h4>			
	@include('teacher::tabs')
	<!-- <a href="{{route('create-academic-session')}}" type="button" class="btn btn-warning">Create Academic Session</a><br><br> -->
		<div class="box"> 
			<div class="box-body">				
				<div class="table-responsive" id="search-results">
					@if(count($teachers))
						<?php  $i =1; ?>						
			        <table class="table table-bordered table-hover" id="myTable">        
						<thead>
							<tr style="background-color:#333; color:white;">
							<th>SN</th>
							<th>Teacher ID</th>							
							<th>Teacher Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Role</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
						@foreach($teachers as $index => $teacher)
							<tr>
							<td>{{$i++}}</td>
							<td>{{$teacher['teacher_id']}}</td>
							<td>{{$teacher['name'] }}</td>
							<td>{{$teacher['email'] }}</td>
							<td>{{$teacher['phone'] }}</td>
							<td>
								@foreach(json_decode($teacher['role']) as $index => $d)
									{{$d->role_name}} 
								@endforeach
							</td>										
							<td>
								<!-- <a href = "{{route('view-teacher', $teacher['id'])}}" class="view-student"><button data-toggle="tooltip" title="" class="btn btn-default btn-flat" type="button" data-original-title="View" > <i class="fa fa-fw fa-file"></i></button> -->
								<a class="btn btn-success btn-flat"  data-toggle="modal"data-target="#viewTeacher{{$teacher['id']}}" data-title="View Teacher" data-message="View Teacher">
      							<i class="glyphicon glyphicon-file"></i> 
      							</a> 			
      							@include('teacher::modal.teacher-view-modal')	
								

								<a href = "{{route('edit-teacher', $teacher['id'])}}"><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> <i class="fa fa-fw fa-edit"></i></button></a>

								<a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#confirmDelete{{$teacher['id']}}" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
      							<i class="glyphicon glyphicon-trash"></i> 
      							</a> 			
								@include('teacher::modal.delete-teacher-modal')									 
							</td>
							</tr>
						@endforeach
						</tbody>       								
			        </table>			        
					@else
					<div class="alert alert-warning alert-dismissable">
	  					<h4><i class="icon fa fa-warning"></i>NO TEACHERS AVAILABLE</h4>
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
<script>
    $(document).ready( function () {
    	$('#myTable').DataTable();
	});
</script>
@endsection