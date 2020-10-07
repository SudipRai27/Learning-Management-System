@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/form.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/plugins/daterangepicker/daterangepicker.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
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
	@if($role == "admin")
	<h4><b>Event Manager</b></h4>	
	@include('event::tabs')	
	@else
	<h4><b>Displaying Search Results</b></h4>	
	@endif
		<div class="box"> 
			<div class="box-body">								
				@include('event::event-partial')
			</div>			
	 		@if(count($event_list))
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="event-table">     
					<?php $i = 1; ?>
					<thead>
						<tr style="background-color:#333; color:white;">
							<th>SN</th>
							<th>Event Title</th>														
							<th>Start Date</th>
							<th>End Date</th>
							<th>Event For</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($event_list as $index => $record)
						<tr>
							<td>{{$i++}}</td>
							<td>{{$record['event_title']}}</td>							
							<td>{{ date("M-d-Y", strtotime($record['start_date']))}}</td>
							<td>{{ date("M-d-Y", strtotime($record['end_date']))}}</td>
							<td> 
								@foreach(json_decode($record['event_for']) as $rec => $value)
								<span class="primary-badge">{{$value}}</span>
								@endforeach
							</td>
							<td>
								@if($role == "admin")
								<a href = "{{route('view-event',['backend', $record['id']])}}"><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="View Event"> <i class="fa fa-fw fa-file"></i></button></a>

								<a href = "{{route('edit-event', $record['id'])}}"><button data-toggle="tooltip" title="" class="btn btn-success btn-flat" type="button" data-original-title="Edit Event"> <i class="fa fa-fw fa-edit"></i></button></a>

		                        <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteEvent{{$record['id']}}" data-title="Delete Event" data-message="Are you sure you want to delete this event ?">
      							 <i class="glyphicon glyphicon-trash"></i> 
      							</a> 			
      							@else
      							<a href = "{{route('view-event',['frontend', $record['id']])}}" data-lity><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="View Event"> <i class="fa fa-fw fa-file"></i></button></a>
      							@endif
		      					@include('event::modal.event-delete-modal')							
							</td>
						</tr>
						@endforeach								
					</tbody>       								
			    </table>
			</div>		
			@else
			<div class="alert alert-danger alert-dismissable">
                <h4><i class="icon fa fa-warning"></i>Events for your search criteria is not found</h4>
            </div>  
			@endif
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/js/lity.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/plugins/daterangepicker/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
    $(document).ready( function () {
    	$('#event-table').DataTable();
    	$('.select2').select2();
    	$('#daterange').daterangepicker({
      		autoUpdateInput: false,
      		locale: {
          		cancelLabel: 'Clear'
      		}
  		});

	  	$('#daterange').on('apply.daterangepicker', function(ev, picker) {
	    	$(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
	  	});

	  	$('#daterange').on('cancel.daterangepicker', function(ev, picker) {
	    	$(this).val('');
	  	});

	  	$('#event-btn').on('click', (e) => {
	  		e.preventDefault();
	  		const event_for = $('#event_for').val(); 
	  		console.log(event_for);
	  		if(!event_for)
	  		{
	  			toastr.error('Please select event for !!!,  Date range is optional');
	  		}
	  		else
	  		{
	  			$('#event-form').submit();
	  		}
	  	})

 	});

</script>
@endsection