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

</style>
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Attendance Manager</b></h4>		
		<div class="box"> 
			<div class="box-body">								
				@include('attendance::tabs')				
				<div class="form-header"><p class="form-header">Create Attendance</p></div>
				<div class="form-header2">Attendance </div>
				@include('backend.partials.errors')								
				@if($role === "teacher")
					@include('attendance::create-attendance-teacher-view')	
				@else 
					@include('attendance::create-attendance-admin-view')		
				@endif									
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="role" value="{{$role}}">
@if($role == "teacher")
<input type="hidden" value="{{$teacher_id}}" id="teacher_id">
@endif
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/js/lity.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	
	$(document).ready(function (){
		$('.select2').select2();
		$('#class-table').DataTable();

		$('#subject_id').on('change', () => {			
			updateClassList(); 
		})

		$('#session_id').on('change', () => {			
			updateClassList();						
		});

		let updateClassList = () => {
			const subject_id = $('#subject_id').val(); 
			const session_id = $('#session_id').val();
			if(session_id != 0  && subject_id)
			{
				let current_url = $('#current_url').val(); 
				current_url += `?session_id=${session_id}&subject_id=${subject_id}`;
				location.replace(current_url);
			}			
			else
			{
				$('.class-list').html('<div class="alert alert-warning alert-dismissable"><h4><i class="icon fa fa-warning"></i>Please select session and subject</h4></div>');
			}
		}

		if($('#role').val() == "teacher")
    	{   		
    		const updateTeacherClassList = (session_id, teacher_id) => {
    			if(session_id && teacher_id)
				{
					let current_url = $('#current_url').val(); 
					current_url += `?session_id=${session_id}&teacher_id=${teacher_id}`;
					location.replace(current_url);
				}			
				else
				{
					$('.class-list').html('<div class="alert alert-warning alert-dismissable"><h4><i class="icon fa fa-warning"></i>Please select session</h4></div>');
				}
	    		
	    	}

    		$('#session_id').on('change', function () {    			     
                updateTeacherClassList($(this).val(), $('#teacher_id').val());
           	});    	
    	}
	});
</script>
@endsection