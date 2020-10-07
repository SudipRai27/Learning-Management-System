@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
<style type="text/css">
	.header {
		text-align: center;		
		padding: 5px 0px;
		font-weight: bold;
		width:50%;
		margin:auto;
		margin-top: 20px;
		margin-bottom: 10px;
		background-color: #222d32;
		color:white;		
	}

	.table-responsive {
		margin-top: 10px;
	}
</style>
@endsection
@section('content')
<h4><b>TimeTable</b></h4>	
@include('timetable::tabs')	
<div class="form-header">
	<p class="form-header">Create TimeTable</p>
</div>
<div class="box-body">
	@include('backend.partials.errors')
	<div class="form-header2">Create / Update</div>
	<div class="row">			
		<div class="col-sm-12">
			<div class="form-group">
				@if($role == "student")
					@include('timetable::student.add-edit-timetable')
				@else
					@include('timetable::admin.add-edit-timetable')
				@endif
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="role" value="{{$role}}">
@if($role == "student")
<input type="hidden" id="secondary_student_id" value="{{$student_id}}">
@endif
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/js/lity.min.js')}}"></script>
<script type="text/javascript">
	const config = {
        routes: {            
            updateStudentAutoCompleteRoute: '{{ route("get-student-autocomplete")}}',             
        }
    };


	$(document).ready(function (){
		$('.select2').select2();
		$('#timetable-table').DataTable();		
	});

	if($('#role').val() == "student")
	{
		$('#session_id').on('change', function() {

			let session_id = $(this).val();			
			if(session_id != 0) {
				updateEnrolledSubjectsAndClasses(session_id, $('#secondary_student_id').val());	
			}
			else
			{
				$('.subject-list').html('<div class="alert alert-warning alert-dismissable"><h4><i class="icon fa fa-warning"></i>Please select session</h4></div>');
			}
            
		});
	}
	else 
	{
		$('#session_id').on('change', function() {
			let session_id = $(this).val();	
			if(session_id != 0)
			{
				updateStudentSelectList(session_id);
				if($('#student_id').val())	
				{
					updateEnrolledSubjectsAndClasses(session_id, $('#student_id').val());
				}
			}
			else 
			{
				$('.subject-list').html('<div class="alert alert-warning alert-dismissable"><h4><i class="icon fa fa-warning"></i>Please select session and student</h4></div>');
			}
			
		});

		if($('#session_id').val())
		{
			updateStudentSelectList($('#session_id').val());
		}
	}

	function updateStudentSelectList(session_id)
	{
		$.ajax({
			type: 'GET', 
			url: '{{route("ajax-get-enrolled-student-from-session")}}', 
			data: {	
				session_id, 
				selected_student_id : $('#selected_student_id').val()
			}, 
			success:function(data)
			{
				$('#student_id').html(data);
			}, 
			error: function(jqXHR, textStatus)
			{
				 toastr.error("Request Failed : " + textStatus);
			}
		});
	}

	$('#student_id').on('change', function () {		
		const session_id = $('#session_id').val();		
		const student_id = $('#student_id').val();
		updateEnrolledSubjectsAndClasses(session_id, student_id);
	})

	function updateEnrolledSubjectsAndClasses(session_id, student_id)
	{
		let current_url = $('#current_url').val(); 
		current_url += '?session_id='+ session_id + '&student_id=' + student_id;
		location.replace(current_url);
		
	}
</script>
@endsection