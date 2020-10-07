@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<style type="text/css">
	.table-responsive {
		margin-top: 10px;
	}
	.alert-dismissable {
		margin-top: 10px;
	}
</style>
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Room Manager</b></h4>		
		<div class="box"> 
			<div class="box-body">								
				<a href="{{route('create-room')}}" class="btn btn-primary btn-flat" data-lity>Create Room</a>		
				@if(count($rooms))	
				<?php
					$i = 1;
				?>
				<div class="table-responsive">
					<table class="table table-bordered table-hover" id="room-table">        
						<thead>
							<tr style="background-color:#333; color:white;">
							<th>SN</th>
							<th>Room Code</th>							
							<th>Description</th>
							<th>Room Type</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
						@foreach($rooms as $index => $d)						
							<tr>
							<td>{{$i++}}</td>
							<td>{{$d->room_code}}</td>
							<td>{{$d->description }}</td>
							<td>{{$d->room_type }}</td>									
							<td>
								<a href = "{{route('edit-room', $d->id)}}" data-lity><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> <i class="fa fa-fw fa-edit"></i></button></a>	

								<a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteRoom{{$d->id}}" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
      							<i class="glyphicon glyphicon-trash"></i> 
      							</a> 			
								@include('room::modal.room-delete-modal')						                       
							</td>	
							</tr>
						@endforeach
						</tbody>       								
				    </table>
				</div>
				@else
				<div class="alert alert-danger alert-dismissable">
	  				<h4><i class="icon fa fa-warning"></i>No Rooms Available</h4>
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
<script>
    $(document).ready( function () {
    	$('#room-table').DataTable();
	});

	$(document).on('lity:close', function() {
        location.reload();
    });
</script>
@endsection