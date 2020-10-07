@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/form.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/plugins/daterangepicker/daterangepicker.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
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
	@if($role=="admin")
	<h4><b>Event Manager</b></h4>	
		@include('event::tabs')	
	@else
	<h4><b>Search for events here</b></h4>
	@endif
		<div class="box"> 
			<div class="box-body">								
				@include('event::event-partial')
			</div>
			<div class="alert alert-warning alert-dismissable">
	  			<h4><i class="icon fa fa-warning"></i>Please select event for , date range is optional</h4>
	 		</div>	
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript" src="{{asset('public/sms/assets/js/lity.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/plugins/daterangepicker/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
	let startDate;
	let endDate
    $(document).ready( function () {
    	$('#event-table').DataTable();
    	$('.select2').select2();
    	$('#daterange').daterangepicker({
      		autoUpdateInput: false,
      		locale: {
          		cancelLabel: 'Clear', 
          		format: 'YYYY-MM-D',
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