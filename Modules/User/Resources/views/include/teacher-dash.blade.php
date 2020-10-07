@extends('backend.main')
@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
@endsection
@section('content')

<?php
	$published_notice = (new \App\Http\Controllers\HelperController)->getPublishedNotice();
	$teacher_id = (new \Modules\Teacher\Entities\Teacher)->getTeacherIdFromUserId(Auth::id());
	$teacher_assigned_years = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
	$events = (new \App\Http\Controllers\HelperController)->getFrontendEvents('teacher');
	
?>	
<div class="breadcrumbs">
	<span class="breadcrumb-link"><a href="{{route('user-home')}}">Dashboard</a> </span>	
</div>
<br>
<div class="row dashboard">
	<div class="col-sm-8 dashboard-content">
		<div class="slider-carousel" style="margin-bottom: 2rem;">
			@include('backend.partials.slider-carousel-partial')
		</div>
		<div class="box box-primary notices"><!-- box upcoming events starts -->
	        <div class="box-header">
	            <i class="ion ion-clipboard"></i>
	            <h3 class="box-title">Notice</h3>
	            <br>
                <br>
              	@if(strlen($published_notice['notice']) > 0 && strlen($published_notice['created_at']) > 0)
	                <p>{{$published_notice['notice']}}</p>
	                <p>{{$published_notice['created_at']}}</p>
	            @else
	            	<p>Current Notice Unavailable</p>
            	@endif
	        </div>
     	</div>
        <!-- <div class="box-body">
            <ul class="todo-list">
     	        <li>
        	        <span class="handle">
                	    <i class="fa fa-ellipsis-v"></i>
            	    </span>
                  	<span class="text"><a href=""></a></span>
                  	<small class="label label-danger"><i class="fa fa-clock-o"></i></small>
                </li>
            </ul>
        </div>       -->
        <div class="box box-warning my-courses"><!-- box upcoming events starts -->
	        <div class="box-header">
	            <i class="ion ion-clipboard"></i>
	            <h3 class="box-title">Assigned Courses</h3>
	        </div>
     	</div>
        <div class="box-body">
        	<div class="row">
        		<div class="col-sm-4">
		        	<div class="select-box">
		        		<label>Session:</label>
						<select class="form-control select2" id="session_id">
							<option value="">Select</option>
							@foreach($teacher_assigned_years as $index => $record)		
							<option value="{{$record->id}}"
								@if($record->is_current == 'yes')
								selected 
								@endif
							>{{$record->session_name}} {{$record->is_current == "yes" ? "-- Current Session --" : ''}}</option>
							@endforeach
						</select>
						<input type="hidden" id="teacher_id" value="{{$teacher_id}}">
		        	</div>
		        </div>
		    </div>
        	<div id="assigned-subjects">
			</div>       
        </div>      
    </div>
	<div class="col-sm-4 dashboard-sidebar">
		<div class="panel panel-default upcoming-events">
		  <div class="panel-heading">Upcoming Events</div>
		  <div class="panel-body">
		  	@foreach($events as $index => $record)  
		  	
              <div style="margin-bottom: 2rem !important;">
                <span class="primary-badge">
                {{ date("M d Y", strtotime($record['start_date']))}}
                </span> 
                to
                <span class="error-badge">
                  {{ date("M d Y", strtotime($record['end_date']))}}
                </span>
                <h4 style="margin: 2rem 0;"><a href="{{route('view-event',['frontend',$record['id']])}}" data-lity style="color:black !important;">{{$record['event_title']}}</a></h4>
              </div>
              <?php
              if($index == 3)
              {
              	break;
              }
              ?>          
            @endforeach
            <a href="{{route('list-event')}}" style="color:black;"> View All Events <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
		  </div>
		</div>		
	</div>
</div>
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/js/lity.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.select2').select2();
		$('#session_id').on('change', () => {
			if($('#session_id').val())
			{
				updateEnrolledSubjects($('#session_id').val());
			}
			else
			{
				$('#assigned-subjects').html('<div class="alert alert-warning alert-dismissable"><h4><i class="icon fa fa-warning"></i>Please select session</h4></div>');
			}
		})
		const updateEnrolledSubjects = (session_id) => {
			$('#assigned-subjects').html('<p align="center"><img src = "{{ asset('public/images/loader.gif')}}"></p>');
			$.ajax({
				url : '{{route("ajax-get-enrolled-subjects-dashboard-student-teacher")}}', 
				data: {
					session_id, 
					teacher_id: $('#teacher_id').val(), 
					role: 'teacher'
				}, 
				type: 'GET', 
				success:function(data)
				{					
					$('#assigned-subjects').html(data);			
				}, 
				error:function(jqXHR, textStatus)
				{					
					toastr.error(textStatus);
				}

			});
		}
		if($('#session_id').val())
		{
			updateEnrolledSubjects($('#session_id').val());	
		}		

		$(document).on('click', '.dashboard-subject-btn',function(e) {
			e.preventDefault();
			const subject_id = $(this).prev().val();
			const session_id = $('#session_id').val();
			const url = "{{url('/')}}";			
			window.location.href = url + '/subject/frontend-subject-details/' + session_id + '/' + subject_id;
		});

	});


</script>
@endsection