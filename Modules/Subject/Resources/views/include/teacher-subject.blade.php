@extends('backend.main')
@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('content')

<?php
	
	$teacher_id = (new \Modules\Teacher\Entities\Teacher)->getTeacherIdFromUserId(Auth::id());
	$teacher_assigned_years = (new \Modules\Teacher\Entities\AssignTeacher)->getTeacherAssignedYears($teacher_id);
	
?>	
<h3 class="assigned-enrolled-subject-heading">My Assigned Subjects</h3>
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
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
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