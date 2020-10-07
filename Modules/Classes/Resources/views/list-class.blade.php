@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"> 
<style type="text/css">
	.table-responsive {
		margin-top: 10px;
	}
	.alert-dismissable {
		margin-top: 10px;
	}

	.select2-container .select2-selection--single {
    height:34px !important;
	}

	.select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important; 
    	border-radius: 0px !important; 
	}
	.table-responsive {
		margin-top: 20px;
	}

</style>
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Classes Manager</b></h4>		
		<div class="box"> 
			<div class="box-body">								
				<a href="#" class="btn btn-primary btn-flat" id="create-and-check-session">Create Class</a>	
				@include('academicsession::modal.error-modal')
				<br><br>
				<div class="row">	
					<div class="col-sm-6">
						@include('backend.partials.academic-session-partial')
					</div>
					<div class="col-sm-6 select-div">
					    <label>Subject: </label><br>
					    <select class="form-control select2" id="subject_id" name="subject_id">
					        <option value="">Select</option>
					        @foreach($subjects as $index => $subject)
					        <option value="{{$subject->id}}"
					        	@if($subject->id == $selected_subject_id)
					        	selected
					        	@endif
					        	>{{$subject->subject_name}} -- {{$subject->course_type}} -- {{$subject->course_title}}</option>
					        @endforeach
					    </select>
					</div>
				</div>
				@if($selected_session_id == 0)
					<div class="alert alert-warning alert-dismissable">
                        <h4><i class="icon fa fa-warning"></i>Please select academic session first</h4>
                    </div>  
				@else
					@if(count($class_list))
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="class-table">     
							<?php $i = 1; ?>
							<thead>
								<tr style="background-color:#333; color:white;">
									<th>SN</th>
									<th>Room Code</th>							
									<th>Teacher Name</th>
									<th>Subject Name</th>
									<th>Class Type</th>
									<th>Day</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($class_list as $index => $record)
								<tr>
									<td>{{$i++}}</td>
									<td>{{$record->room_code}}</td>
									<td>{{$record->teacher_name}}</td>
									<td>{{$record->subject_name}}</td>
									<td>{{$record->type}}</td>
									<td> {{ (new \App\DayAndDateTime)->returnDayName($record->day_id) }}
									</td>
									<td>
										<a class="btn btn-warning btn-flat"  data-toggle="modal" data-target="#viewClasses{{$record->id}}" data-title="View Student" data-message="View Student">
		      							<i class="glyphicon glyphicon-file"></i> 
		      							</a> 			
										@include('classes::modal.classes-view-modal')	

										<a href = "{{route('edit-classes', $record->id)}}" data-lity><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> <i class="fa fa-fw fa-edit"></i></button></a>		                       
				                        <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteClass{{$record->id}}" data-title="Delete Class" data-message="Are you sure you want to delete this class ?">
		      							 <i class="glyphicon glyphicon-trash"></i> 
		      							</a> 			

		      							@include('classes::modal.classes-delete-modal')										
									</td>
								</tr>
								@endforeach								
							</tbody>       								
					    </table>
					</div>		
					@else
					<div class="alert alert-danger alert-dismissable">
                        <h4><i class="icon fa fa-warning"></i>Classes not found</h4>
                    </div>  
					@endif
				@endif
						
			</div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript" src="{{asset('public/sms/assets/js/lity.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/check-current-session-function.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>

	const config = {
		routes : {
			checkCurrentSessionRoute: '{{route("ajax-check-current-session")}}'
		}
	}


    $(document).ready( function () {
    	$('#class-table').DataTable();
    	$('.select2').select2();


	$('#create-and-check-session').on('click', function (e) {
		e.preventDefault();
		e.stopImmediatePropagation();

		let result = checkCurrentSession();
    	result.success(function (response) {
    		if(response == "found")
    		{
    			let lightbox = lity('{{route("create-class")}}');
    		}
    		else
    		{
    			$('.error-msg').text("No session has been set to current. Please set a current session and try again later.");
    			$('#errorModal').modal();

    		}
    	});
    	result.error(function(jqXHR, textStatus) {
    		toastr.error("error" + textStatus);
    	})
	});

  
    	$('#session_id').on('change', function() {
    		let session_id = $(this).val();
    		let subject_id = $('#subject_id').val();
    		updateClassList(session_id, subject_id);
    	});

    	$('#subject_id').on('change', function() {
    		subject_id = $(this).val();
    		session_id = $('#session_id').val();
    		updateClassList(session_id, subject_id);

    	})

    	function updateClassList(session_id, subject_id)
    	{
    		let current_url = $('#current_url').val(); 
    		current_url += '?session_id=' + session_id + '&subject_id='+ subject_id;
    		location.replace(current_url);
    	}
	});

	$(document).on('lity:close', function() {
        location.reload();
    });
</script>
@endsection