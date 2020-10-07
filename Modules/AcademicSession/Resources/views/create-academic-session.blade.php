@extends('backend.main')
@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />    
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Academic Session Manager</b></h4>
	@include('academicsession::tabs')
	<!-- <a href="{{route('list-academic-session')}}" type="button" class="btn btn-danger">Go Back</a><br><br> -->
		<div class="box"> 
			<div class="box-body">		
				<div class="alert alert-warning alert-dismissable">
	  				<p><i class="icon fa fa-warning"></i>By default, students will not be able to enroll or update timetable and teachers will not be able to do attendance for the newly created session. This can be changed later in the settings page.</p>
	  			</div>	
				@if ($errors->any())
                <div class = "alert alert-danger alert-dissmissable">
                <button type = "button" class = "close" data-dismiss = "alert">X</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
          		@endif
          		<div class="form-header"><p class="form-header">Create Academic Session</p></div>
				<form action="{{route('create-academic-session')}}" method="POST" enctype="multipart/form-data" class="crud-form">
					<div class="box-body">
						<div class="form-group">
						<label>Academic Session</label>
						<input type="text" name="session_name" id= "session_name" class="form-control @if($errors->first('session_name')) form-error @endif" value="{{ Input::old('session_name')}}" placeholder="Academic Session">
						</div>
						<div class="row">
							<div class="col-sm-4">
								<label>Is Current / Active: </label>
								<select class="form-control @if($errors->first('is_current')) form-error @endif" name="is_current" id="is_current">
									<option value="">Select</option>
									<option value="yes" @if(Input::old('is_current') == 'yes') selected @endif >Yes</option>
									<option value="no" @if(Input::old('is_current') == 'no') selected @endif> No</option>
								</select>
							</div>						
						
							<div class="col-sm-4">
								<label>Start Date</label>
	  							<div id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
	   							<input class="form-control @if($errors->first('start_date')) form-error @endif" type="text" readonly name="start_date" value="{{Input::old('start_date')}}" id="start_date" />
	    							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								</div>
							</div>
							<div class="col-sm-4">
								<label>End Date</label>
	  							<div id="datepicker1" class="input-group date" data-date-format="mm-dd-yyyy">
	   							<input class="form-control @if($errors->first('end_date')) form-error @endif" type="text" readonly name="end_date" value="{{Input::old('end_date')}}" id="end_date" />
	    							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								</div>
							</div>
						</div>
						<br>
						<input type="submit" value="Create" class="btn btn-primary">
					</div>
				{{ csrf_field() }}
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
	$(function () {
  	$("#datepicker").datepicker({ 
        autoclose: false, 
        todayHighlight: true,
        format: 'yyyy-mm-dd'
  		}).datepicker(new Date());
	});
	$(function () {
  	$("#datepicker1").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format: 'yyyy-mm-dd'
  		}).datepicker(new Date());
	});

	$(function() {
		$('#session_name').keypress(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});	
		$('#start_date').change(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});	
		$('#end_date').change(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});	
		$('#is_current').change(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});	
	});

</script>
@endsection