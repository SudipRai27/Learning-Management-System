@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Admin Manager</b></h4>			
	@include('admin::tabs')
	<!-- <a href="{{route('create-academic-session')}}" type="button" class="btn btn-warning">Create Academic Session</a><br><br> -->
		<div class="box"> 
			<div class="box-body">				
				<div class="table-responsive" id="search-results">
					@if(count($admins))
						<?php  $i =1; ?>						
			        <table class="table table-bordered table-hover" id="admin-table">        
						<thead>
							<tr style="background-color:#333; color:white;">
							<th>SN</th>												
							<th>Name</th>
							<th>Email</th>
							<th>Address</th>
							<th>Phone</th>							
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
						@foreach($admins as $index => $admin)
							<tr>
							<td>{{$i++}}</td>							
							<td>{{$admin->name}}</td>
							<td>{{$admin->email }}</td>
							<td>{{$admin->address }}</td>
							<td>{{$admin->phone }}</td>												
							<td>								
								<a class="btn btn-success btn-flat"  data-toggle="modal"data-target="#viewAdmin{{$admin->id}}" data-title="View Teacher" data-message="View Teacher">
      							<i class="glyphicon glyphicon-file"></i> 
      							</a> 			
      							@include('admin::modal.admin-view-modal')	
								
      							@if(Auth::id() == $admin->id)
								<a href = "{{route('edit-admin', $admin->id)}}"><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> <i class="fa fa-fw fa-edit"></i></button></a>
								@endif
								
								@if(Auth::id() != $admin->id)
								<a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#confirmDelete{{$admin->id}}" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
      							<i class="glyphicon glyphicon-trash"></i> 
      							</a> 			
								@include('admin::modal.delete-admin-modal')									 
								@endif
							</td>
							</tr>
						@endforeach
						</tbody>       								
			        </table>			        
					@else
					<div class="alert alert-warning alert-dismissable">
	  					<h4><i class="icon fa fa-warning"></i>NO TEACHERS AVAILABLE</h4>
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
    	$('#admin-table').DataTable();
	});
</script>
@endsection