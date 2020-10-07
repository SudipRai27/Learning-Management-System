@extends('backend.main')
@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('content')
<?php
	$student_id = (new \Modules\Student\Entities\Student)->getStudentId(Auth::id());
	$enrollment_years = (new \Modules\Enrollment\Entities\Enrollment)->getStudentEnrollmentYears($student_id);
	
?>	
<h3 class="assigned-enrolled-subject-heading">My Enrolled Subjects</h3>
<div class="box-body">
	<div class="row">
		<div class="col-sm-4">
        	<div class="select-box">
        		<label>Session:</label>
				<select class="form-control select2" id="session_id">
					<option value="">Select</option>
					@foreach($enrollment_years as $index => $record)		
					<option value="{{$record->id}}"
						@if($record->is_current == 'yes')
						selected 
						@endif
					>{{$record->session_name}} {{$record->is_current == "yes" ? "-- Current Session --" : ''}}</option>
					@endforeach
				</select>
				<input type="hidden" id="student_id" value="{{$student_id}}">
        	</div>
        </div>
    </div>
	<div id="enrolled-subjects">
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
				$('#enrolled-subjects').html('<div class="alert alert-warning alert-dismissable"><h4><i class="icon fa fa-warning"></i>Please select session</h4></div>');	
			}
		})
		const updateEnrolledSubjects = (session_id) => {
			$('#enrolled-subjects').html('<p align="center"><img src = "{{ asset('public/images/loader.gif')}}"></p>');
			$.ajax({
				url : '{{route("ajax-get-enrolled-subjects-dashboard-student-teacher")}}', 
				data: {
					session_id, 
					student_id: $('#student_id').val(), 
					role: 'student'
				}, 
				type: 'GET', 
				success:function(data)
				{
					$('#enrolled-subjects').html(data);
				}, 
				error:function(jqXHR, textStatus)
				{
					console.log(textStatus);
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