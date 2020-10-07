@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<style type="text/css">
	.table-responsive {
		margin-top: 20px;
	}
	.alert-dismissable {
		margin-top: 10px;
	}
	#lecture-table tbody td:nth-child(6) {
		width: 200px;
	}
</style>
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Lecture Manager</b></h4>		
		<div class="box"> 
			<div class="box-body">								
				@include('lecture::tabs')
				<div class="row">
					@include('backend.partials.session-subject-partial-refactored')
				</div>
				@if(!is_null($lecture_list))
				<div class="table-responsive">
					@if(count($lecture_list))
					<table class="table table-bordered table-hover" id="lecture-table">   
						<thead>
							<tr style="background-color:#333; color:white;">
							<th>SN</th>
							<th>Lecture Name</th>							
							<th>Description</th>
							<th>Sort Order</th>							
							<th>Files</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1;?>
						@foreach($lecture_list as $index => $lecture)						
							<tr>
							<td>{{$i++}}</td>
							<td>{{$lecture['lecture_name']}}</td>
							<td>{{$lecture['lecture_description'] }}</td>
							<td>{{$lecture['sort_order'] }}</td>

							<td>
								@if(count($lecture['resources']))
								<ul>
									@foreach($lecture['resources'] as $key => $resource)
										<li><a href="{{$resource['s3_url']}}">{{$resource['filename']}}</li>
									@endforeach
								</ul>
								@else
									No Files
								@endif								
							</td>								
							<td>
								<a href="{{route('manage-resources', $lecture['lecture_id'])}}" class="btn btn-warning btn-flat" data-lity>Resources </a>
								<a href = "{{route('edit-lecture', $lecture['lecture_id'])}}" data-lity><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> <i class="fa fa-fw fa-edit"></i></button></a>		
								<a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteLecture{{$lecture['lecture_id']}}" data-title="Delete Lecture" data-message="Are you sure you want to delete this Lecture ?">
      							<i class="glyphicon glyphicon-trash"></i> 
      							</a> 			
								@include('lecture::modal.delete-lecture-modal')						
							</td>							
							</tr>
						@endforeach
						</tbody>       								
				    </table>
				    @else
				    <div class="alert alert-danger alert-dismissable">
	  					<h4><i class="icon fa fa-warning"></i>Lectures not available</h4>
	 				</div>	
				    @endif
				</div>
				@else
				<div class="alert alert-warning alert-dismissable">
	  				<h4><i class="icon fa fa-warning"></i>Please select session and subject</h4>
	 			</div>	
				@endif	
				<input type="hidden" value="{{$role}}" id="role">	
				<input type="hidden" value="{{$teacher_id}}" id="teacher_id">		
				<input type="hidden" value="{{$selected_subject_id}}" id="selected_subject_id">
				<input type="hidden" value="{{$selected_session_id}}" id="selected_session_id">
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
    	$('#lecture-table').DataTable();
    	$('.select2').select2();


    	if($('#role').val() == "teacher")
    	{   		
    		const updateTeacherAssignedSubjectList = (session_id, teacher_id) => {
	    		$('#subject_id').html('<option>Loading</option>');    		
	    		$.ajax({
	    			type : 'GET', 
	    			url: '{{route("ajax-get-teacher-assigned-subjects-from-session")}}', 
	    			data: {
	    				session_id, teacher_id, 
	    				selected_subject_id: $('#selected_subject_id').val()
	    			}, 
	    			success: function(data) {
	    				$('#subject_id').html(data);
	    			}, 
	    			error: function(jqXHR)
	    			{    				
	    				toastr.error(jqXHR.statusText + " " + jqXHR.status);
	    			}
	    		});
	    	}


    		if($('#session_id').val())
    		{
    			updateTeacherAssignedSubjectList($('#session_id').val(), $('#teacher_id').val());
    		}

    		$('#session_id').on('change', function () {
    			updateTeacherAssignedSubjectList($(this).val(), $('#teacher_id').val());
    		})    	

    	}
    	else 
    	{
    		$('#session_id').on('change', function(){
    			updateLectureListTable();
    		});
    	}

    	
    	$('#subject_id').on('change', function(){
    		updateLectureListTable();
    	});

    	let updateLectureListTable = () => 
    	{
    		const session_id = $('#session_id').val(); 
    		const subject_id = $('#subject_id').val(); 
    		if(session_id && subject_id)
    		{
    			let current_url = $('#current_url').val();
    			current_url += `?session_id=${session_id}&subject_id=${subject_id}`;
    			window.location.replace(current_url);
    		}
    	}

    	$(document).on('lity:close', function() {
        	location.reload();
    	});

    	
	});

	
</script>
@endsection