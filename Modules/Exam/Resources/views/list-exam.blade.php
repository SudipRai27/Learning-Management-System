@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/form.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<style type="text/css">
	.table-responsive {
		margin-top: 20px;
	}
	.alert-dismissable {
		margin-top: 20px;
	}
</style>
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Exam Manager</b></h4>		
		<div class="box"> 
			<div class="box-body">	
				@include('exam::tabs')	
				<div class="row">		
					<div class="col-sm-8">				
						<label>Academic Session: </label>
						<select class="form-control select2" id="session_id" name="session_id">
						    <option value="">Select</option>
						    @if($role == "admin")
						    <option value="all" 
						    @if(isset($selected_session_id))
						        @if($selected_session_id == "all")
						        selected
						        @endif
						        @endif
						        >All</option>
						     @endif
						    @foreach($academic_session as $index => $d)
						    <option value="{{$d->id}}"
						        @if(isset($selected_session_id))
						        @if($d->id == $selected_session_id)
						        selected
						        @endif
						        @endif
						        >{{$d->session_name}} {{ $d->is_current == "yes" ? '--Current Session --' : '' }}
						    </option>                               
						    @endforeach
						</select>    
					</div>
				</div>
				@if(!is_null($exam_list))
					@if(count($exam_list))
					<?php $i=1; ?>
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="exam-table">        
							<thead>
								<tr style="background-color:#333; color:white;">
								<th>SN</th>
								<th>Session</th>
								<th>Exam Name</th>							
								<th>Start Date</th>
								<th>End Date</th>
								<th>Marks</th>
								<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($exam_list as $index => $exam)
								<tr>
									<td>{{$i++}}</td>
									<td>{{$exam->session_name}}</td>
									<td>{{$exam->exam_name}}</td>
									<td>{{$exam->start_date}}</td>
									<td>{{$exam->end_date}}</td>
									<td>{{$exam->marks}}</td>
									<td>
										<a href = "{{route('edit-exam', $exam->id)}}" data-lity><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> <i class="fa fa-fw fa-edit"></i></button></a>	

										<a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteExam{{$exam->id}}" data-title="Delete Lecture" data-message="Are you sure you want to delete this exam ?">
		      							<i class="glyphicon glyphicon-trash"></i> 
		      							</a> 			
										@include('exam::modal.delete-exam-modal')					
									</td>
								</tr>
								@endforeach						
							</tbody>       								
					    </table>
					</div>
					@else
					<div class="alert alert-danger alert-dismissable">
		  				<h4><i class="icon fa fa-warning"></i>Exams not available</h4>
		 			</div>	
		 			@endif
	 			@else
	 			<div class="alert alert-warning alert-dismissable">
	  				<h4><i class="icon fa fa-warning"></i>Please select academic session</h4>
	 			</div>	
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
    	$('#exam-table').DataTable();
    	$('.select2').select2();

    	

    	$('#session_id').on('change', () => {
			const session_id = $('#session_id').val();
			updateExamList(session_id);	
    	});

    	const updateExamList = (session_id) => {
    		console.log(session_id);
    		let current_url = $('#current_url').val();
    		current_url += `?session_id=${session_id}`;
    		location.replace(current_url);

    	}
	});

	$(document).on('lity:close', function() {
        location.reload();
    });
</script>
@endsection