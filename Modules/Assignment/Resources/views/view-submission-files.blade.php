@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
@endsection
@section('content')
<div class="breadcrumbs">
	<span class="breadcrumb-link"><a href="{{route('user-home')}}">Dashboard</a> / </span>
	<span class="breadcrumb-link">My Courses / </span>
	<span class="breadcrumb-link"><a href="{{route('frontend-subject-details', [$session_id, $subject->id])}}">{{$subject->subject_name}}</a>  / </span>
	<span class="breadcrumb-link"><a href="{{route('frontend-view-assignment-submissions', [$session_id, $subject->id, $assignment_id])}}">Assignment Submissions</a>  </span>
</div>
<div class="row subject-details">
	<div class="col-lg-9 col-md-9 col-sm-12">
		<h3 class="subject-heading">Assignment Submissions</h3>
		<h4 class="jumbotron">{{$assignment->title}}</h4>
		<table class="table table-bordered table-hover" id="assignment-submission-table">        
			<thead>
				<tr style="background-color:#333; color:white;">
				<th>SN</th>
				<th>Student</th>							
				<th>Status</th>
				<th>Files</th>
				<th>Submitted at</th>
				<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$i = 1;
			?>
			@foreach($student_submission['submissions'] as $index => $d)						
				<tr>
				<td>{{$i++}}</td>
				<td>{{$d->name }} / {{ $d->uniqueId}}</td>
				<td>
					@if(count($d->submission))
						<p class = "success-badge">Submitted</p>
					@else
						<p class="error-badge">Not Submitted</p>
					@endif
				</td>
				<td>
					@if(count($d->submission))					
						<ul>
						@foreach($d->submission as $index => $resource)
							<li><a href="{{$resource->s3_url}}">{{$resource->filename}}</a></li>
						@endforeach
						</ul>
					@endif
				</td>
				<td>
					@if(isset($d->updated_at))
						{{(new \App\DayAndDateTime)->changeDateFormat($d->updated_at) }}
					@endif
				</td>									
				<td>			
					@if(isset($d->submission_id))
					<a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteSubmission{{$d->submission_id}}" data-title="Delete Submission" data-message="Are you sure you want to delete this user ?">
						<i class="glyphicon glyphicon-trash"></i> 
					</a> 			
					@include('assignment::modal.delete-assigment-submission-modal')					
					@endif                       
				</td>	
				</tr>
			@endforeach
			</tbody>       								
	    </table>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-12 teaching-team">
		<div class="panel panel-default upcoming-events">
		  <div class="panel-heading">Status</div>
		  <div class="panel-body">		   
	   		<p class="info-lead">Total Submissions: {{$student_submission['total_submissions']}}</p>
	   		<p class="info-lead">Total Students: {{$student_submission['total_students']}} </p>
		   
		  </div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
    	$('#assignment-submission-table').DataTable();
	});
</script>
@endsection