@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"> 
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
<style type="text/css">
	.table-responsive {
		margin-top: 20px;
	}
	.alert-dismissable {
		margin-top: 20px;
	}
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
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Attendance Manager</b></h4>		
		<div class="box"> 
			<div class="box-body">								
				@if($role == "teacher")
                    @include('attendance::tabs')
                    @include('attendance::list-attendance-teacher-view')
				@elseif($role == "admin")
                    @include('attendance::tabs')
                    @include('attendance::list-attendance-admin-view')
                @else
                    @include('attendance::list-attendance-student-view')
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript" src="{{asset('public/sms/assets/js/lity.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    $(document).ready( function () {    	
    	$('.select2').select2();
    	$('#class-table').DataTable();
        $('#student-time-table').DataTable();

    	$('#session_id').on('change', function() {
    		updateClassList();
    	})

    	$('#subject_id').on('change', function() {
    		updateClassList();
    	});

    	function updateClassList()
    	{	
    		let session_id = $('#session_id').val(); 
    		let subject_id = $('#subject_id').val(); 
    		if(session_id && subject_id)
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

        $('#secondary_session_id').on('change', function () {
            session_id = $('#secondary_session_id').val(); 
            if(!session_id)
            {
                $('.class-list').html('<div class="alert alert-warning alert-dismissable"><h4><i class="icon fa fa-warning"></i>Please select session</h4></div>');
            }
            else
            {
                current_url = $('#current_url').val(); 
                current_url += `?session_id=${session_id}`;
                location.replace(current_url);
            }      
            
        });

        $('#student_session_id').on('change', function () {
            session_id = $('#student_session_id').val(); 
            if(!session_id)
            {
                $('.class-list').html('<div class="alert alert-warning alert-dismissable"><h4><i class="icon fa fa-warning"></i>Please select session</h4></div>');
            }
            else
            {
                current_url = $('#current_url').val(); 
                current_url += `?session_id=${session_id}`;
                location.replace(current_url);
            }            
        });

	});

</script>
@endsection