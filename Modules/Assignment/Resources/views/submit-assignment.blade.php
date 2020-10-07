@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
@endsection
@section('content')
<div class="breadcrumbs">
	<span class="breadcrumb-link"><a href="{{route('user-home')}}">Dashboard</a> / </span>
	<span class="breadcrumb-link">My Courses / </span>
	<span class="breadcrumb-link"><a href="{{route('frontend-subject-details', [$assignment->session_id, $assignment->subject_id])}}">{{ (new \Modules\Subject\Entities\Subject)->getSubjectNameFromId($assignment->subject_id) }}</a> / </span>
	<span class="breadcrumb-link"><a href=""></a>Assignment / </span>
	<span class="breadcrumb-link"><a href="{{route('submit-assignment', [$assignment->session_id, $assignment->subject_id, $assignment->id])}}"> Submission: {{$assignment->title}} /</a> </span>
</div>
<div>
<?php
	$date = new \App\DayAndDateTime;
?>
<div class="row">
	<div class="col-sm-12">
		<div class="assignment table-responsive">
			<div class="assignment-header">
				<h2>{{$assignment->title}}</h2>				
			</div>
			<div class="assignment-content">
				<h3>Submission Status</h3>
				<table class="table table-hover submission-table">
					<tbody>
						<tr>
							<td class="left-cell">Submission Status</td>
							<td>
								@if($status['submission_status'] == "submitted")
									Submitted
								@else
									Not Submitted
								@endif
							</td>
						</tr>
						<tr>
							<td class="left-cell">Available Marks</td>
							<td>
								{{$assignment->marks}}
							</td>
						</tr>
						<tr>
							<td class="left-cell">Grading Status</td>
							<td>
								@if(isset($status['grading_status']))
									{{$status['grading_status']}}
								@else
									Ungraded
								@endif
							</td>
						</tr>
						<tr>
							<td class="left-cell">Due Date</td>
							<td>{{$date->changeDateFormat($assignment->submission_date)}}</td>
						</tr>						
						<tr>
							<td class="left-cell">Last Modified</td>
							<td>
								@if(isset($status['modified_at']))
									{{$date->changeDateFormat($status['modified_at'])}}
								@endif
							</td>
						</tr>						
					</tbody>
				</table>
				<div class="submission-btn">					
					<a href="{{route('add-submission', $assignment->id)}}" class="btn btn-success btn-flat" data-lity>Add Submission</a>
				</div>				
			</div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript" src="{{asset('public/sms/assets/js/lity.min.js')}}"></script>
<script>
    
	$(document).on('lity:close', function() {
        location.reload();
    });
</script>
@endsection
