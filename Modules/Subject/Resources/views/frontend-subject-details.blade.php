@extends('backend.main')
@section('content')
<div class="breadcrumbs">
	<span class="breadcrumb-link"><a href="{{route('user-home')}}">Dashboard</a> / </span>
	<span class="breadcrumb-link">My Courses / </span>
	<span class="breadcrumb-link"><a href="{{route('frontend-subject-details', [$session_id, $subject->id])}}">{{$subject->subject_name}}</a>  </span>
</div>
<div class="row subject-details">
	<div class="col-lg-8 col-md-8 col-sm-12">
		<div>
			<h3 class="subject-heading">Welcome to {{$subject->subject_name}}</h3>
			<div class="jumbotron">
				<div class="container">
					<h3>Course Information</h3>
					<p>{{$subject->description}}</p>
				</div>
			</div>			
		</div>
		<div class="subject-content">
			<div class="subject-heading">
				Assessment
			</div>
			@foreach($assignment_list as $index => $assignment)						
			<div class="panel panel-default">
		      <div class="panel-heading">{{$assignment['title']}}</div>
		      <div class="panel-body">
		      	<div class="panel-subheading">
		      		{{$assignment['description']}}
		      	</div>
		      	<div class="files">
		      		<div class="files-heading">
		      			Resources<br>		      			
		      		</div>
		      		<ul class="resources">
		      		@if(count($assignment['resources']))
						@foreach($assignment['resources'] as $key => $resource)
						
						<li><a href="{{$resource['s3_url']}}" target="_blank"><i class="fa fa-file"></i>{{$resource['filename']}}</a></li>
						
						@endforeach
					@else
						No Files
					@endif	
					</ul>		
					<p class="lead-text">Due Date: {{$assignment['submission_date']}}</p>
		      	</div>
		      	<div class="assignment-submission">
		      		<i class="fa fa-hand-o-right fa-2x"></i>&nbsp;
		      		@if($role == "student")
			      		<a href="{{route('submit-assignment', [$session_id, $subject->id, $assignment['assignment_id']])}}" class="submission-link">  Submit Assignment Here</a>
		      		@endif
		      		@if($role == "teacher")
		      			<a href="{{route('frontend-view-assignment-submissions', [$session_id, $subject->id, $assignment['assignment_id']])}}" class="submission-link">  View Assignment Submissions Here</a>
		      		@endif
		      	</div>
		      </div>
		    </div>
		    @endforeach
			<div class="subject-heading">
				Lectures
			</div>
			@foreach($lectures as $index => $lecture)		
			<div class="panel panel-default">
		      <div class="panel-heading">{{$lecture['lecture_name']}}</div>
		      <div class="panel-body">
		      	<div class="panel-subheading">
		      	{{$lecture['lecture_description']}}
		      	</div>
		      	<div class="files">
		      		<div class="files-heading">
		      			Resources
		      		</div>
		      		<ul class="resources">
			      	@if(count($lecture['resources']))
						@foreach($lecture['resources'] as $key => $resource)
							<li><a href="{{$resource['s3_url']}}" target="_blank"><i class="fa fa-file"></i>{{$resource['filename']}}</a></li>
						@endforeach
					@else
						No Files
					@endif	
					</ul>			
				</div>				
		      </div>
		    </div>
		    @endforeach
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-12 teaching-team">
		<div class="teaching-team-inner">
			<div class="teaching-team-heading">
				Teaching team
			</div>
			<div class="teaching-team-content">
				@foreach($subject->teachers as $index => $teacher)
				<div class="teacher-card">
					<div class="teacher-card-row">
						<h4>{{$teacher->name}}</h4>						
					</div>
					<div class="teacher-card-contact">
						<h4><i class="fa fa-envelope"></i> &nbsp;Email: {{$teacher->email}}</h4>
					</div>
				</div>
				@endforeach				
			</div>
		</div>
	</div>
</div>
@endsection