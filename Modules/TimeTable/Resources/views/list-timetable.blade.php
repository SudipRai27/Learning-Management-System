@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"> 
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>TimeTable</b></h4>	
		@include('timetable::tabs')
		<div class="box"> 
			<div class="box-body">			
				<div class="row">	
					@if($role == "student")	
					<div class="col-sm-8">
					<label>Academic Session: </label>
						<select class="form-control select2" id="secondary_session_id" name="secondary_session_id">
						    <option value="">Select</option>
						    @foreach($academic_session as $index => $d)
                            <option value="{{$d->id}}"
                                @if(isset($selected_session_id))
                                @if($d->id == $selected_session_id)
                                selected
                                @endif
                                @endif
                                >{{$d->session_name}} {{$d->is_current == "yes" ? "-- Current Session --" : ''}}
                            </option>                               
                            @endforeach
						</select>    
					</div>
					@else
					@include('backend.partials.session-student-partial')
					@endif
				</div>
			</div>
			@if(!is_null($student_subject_timetable) && count($student_subject_timetable))
			<div class="table-responsive">									
		        <table class="table table-bordered table-hover" id="time-table">    
					<thead>
						<tr style="background-color:#333; color:white;">
						<th>SN</th>
						<th>Subject</th>
						<th>Room Code</th>
						<th>Teacher </th>									
						<th>Class Type</th>
						<th>Day</th>
						<th>Start Time</th>
						<th>End Time</th>		
						<th>Action</th>				
						</tr>
					</thead>
					<?php $i=1; ?>
					<tbody>
						@foreach($student_subject_timetable as $index => $record)
							@foreach($record as $index => $class)
							<tr>
								<td>{{$i++}}</td>
								<td>{{(new Modules\Subject\Entities\Subject)->getSubjectNameFromId($class->subject_id)}}</td>
								<td>{{(new Modules\Room\Entities\Room)->getRoomNameFromId($class->room_id)}}</td>
								<td>{{(new Modules\Teacher\Entities\Teacher)->getTeacherNameFromId($class->teacher_id)}}</td>
								<td>{{$class->type}}</td>
								<td>{{(new \App\DayAndDateTime)->returnDayName($class->day_id)}}</td>
								<td>{{(new \App\DayAndDateTime)->parseTimein12HourFormat($class->start_time)}}</td>
								<td>{{(new \App\DayAndDateTime)->parseTimein12HourFormat($class->end_time)}}</td>
								<td>
									@if($role != "student")
									<a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteTimeTable{{$class->timetable_id}}" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
      								<i class="glyphicon glyphicon-trash"></i> 
      								</a> 			
									@include('timetable::modal.timetable-delete-modal')	
									@else
									--
									@endif		
								</td>							
							</tr>
							@endforeach
						@endforeach
					</tbody>       								
		        </table>
	    	</div>   
	    	@endif
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/check-current-session-function.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-enrolled-student-function.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
	const config = {
		routes : {
			checkCurrentSessionRoute: '{{route("ajax-check-current-session")}}', 
			getEnrolledStudentListRoute:'{{route("ajax-get-enrolled-student-from-session")}}'
		}
	}

    $(document).ready( function () {
    	
    	$('.select2').select2();
    	$('#time-table').DataTable();

    	if($('#session_id').val())
    	{
    		updateStudentList($('#session_id').val());
    	}


    	$('#session_id').on('change', function () {
    		let session_id = $('#session_id').val();
    		updateStudentList(session_id);
    	})

    	$('#student_id').on('change', function() {
    		session_id = $('#session_id').val();
    		let student_id = $(this).val();
    		updateStudentTimeTableList(session_id, student_id);

    	})

    	function updateStudentTimeTableList(session_id, student_id)
    	{
    		let current_url = $('#current_url').val(); 
    		current_url += '?session_id=' + session_id + '&student_id=' + student_id;
    		location.replace(current_url);
    	}


    	$('#secondary_session_id').on('change', function() {
    		session_id = $('#secondary_session_id').val();
    		current_url = $('#current_url').val();
    		current_url += `?session_id=${session_id}`;
    		location.replace(current_url);
    	});	
    	
	});
</script>
@endsection

