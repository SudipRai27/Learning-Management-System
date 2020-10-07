@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Academic Session Manager</b></h4>			
	@include('academicsession::tabs')
	<!-- <a href="{{route('create-academic-session')}}" type="button" class="btn btn-warning">Create Academic Session</a><br><br> -->
		<div class="box"> 
			<div class="box-body">				
				<div class="table-responsive">
					@if(count($academic_session))
						<?php  $i =1; ?>						
			        <table class="table table-bordered table-hover" id="normal-session-table">        
						<thead>
							<tr style="background-color:#333; color:white;">
							<th>SN</th>
							<th>Academic Session</th>							
							<th>Is Current</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
						@foreach($academic_session as $index => $d)
							<tr>
							<td>{{$i++}}</td>
							<td>{{$d->session_name}}</td>
							<td>{{$d->is_current }}</td>
							<td>{{$d->start_date }}</td>
							<td>{{$d->end_date }}</td>
							<td>
								<a href = "{{route('edit-academic-session', $d->id)}}"><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> <i class="fa fa-fw fa-edit"></i></button></a>
		                        <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#confirmDelete{{$d->id}}" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
      							<i class="glyphicon glyphicon-trash"></i> 
      							</a> 			
								@include('academicsession::modal.academicsession-delete-modal')	
							</td>
							</tr>
						@endforeach
						</tbody>       								
			        </table>
					@else
					<div class="alert alert-warning alert-dismissable">
	  					<h4><i class="icon fa fa-warning"></i>NO ACADEMIC SESSION AVAILABLE</h4>
	 				</div>	
					@endif						
				</div>							
			</div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
    	$('#normal-session-table').DataTable();
	});
</script>
@endsection

