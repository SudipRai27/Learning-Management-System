@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Courses Manager</b></h4>	
	@include('course::tabs')		
	<!-- <a href="{{route('create-course')}}" type="button" class="btn btn-success">Create Course </a><br><br> -->
		<div class="box"> 
			<div class="box-body">				
				<div class="table-responsive">
					@if(count($course))
						<?php  $i =1; ?>						
			        <table class="table table-bordered table-hover" id="normal-course-table">        
						<thead>
							<tr style="background-color:#333; color:white;">
							<th>SN</th>
							<th>Course Title</th>
							<th>Course Type </th>									
							<th>Description</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
						@foreach($course as $index => $d)
							<tr>
							<td>{{$i++}}</td>
							<td>{{$d->course_title}}</td>
							<td>{{$d->course_type}}</td>
							<td>{{$d->description }}</td>					
							<td>
								<a href = "{{route('edit-course', $d->id)}}"><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> <i class="fa fa-fw fa-edit"></i></button></a>		                        
		                        <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#confirmDelete{{$d->id}}" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
      							<i class="glyphicon glyphicon-trash"></i> 
      							</a> 			
								@include('course::course.modal.course-delete-modal')	
							</td>
							</tr>
						@endforeach
						</tbody>       								
			        </table>
					@else
					<div class="alert alert-warning alert-dismissable">
	  					<h4><i class="icon fa fa-warning"></i>COURSE NOT AVAILABLE</h4>
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
    	$('#normal-course-table').DataTable();
	});
</script>
@endsection

