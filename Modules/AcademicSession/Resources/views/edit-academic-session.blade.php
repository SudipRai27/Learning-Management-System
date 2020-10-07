@extends('backend.main')
@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />    
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Update Academic Session</b></h4>
	<a href="{{route('list-academic-session')}}" type="button" class="btn btn-danger">Go Back</a><br><br>
		<div class="box"> 
			<div class="box-body">							
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
          		<div class="form-header"><p class="form-header">Update Academic Session</p></div>
				<form action="{{route('update-academic-session', $academic_session->id)}}" method="POST" enctype="multipart/form-data" class="crud-form">
					<div class="box-body">
						<div class="form-group">
						<label>Academic Session</label>
						<input type="text" name="session_name" id= "session_name" class="form-control @if($errors->first('session_name')) form-error @endif" class="form-control" value="{{ $academic_session->session_name}}" placeholder="Academic Session">
						</div>
						<div class="row">
							<div class="col-sm-4">
								<label>Is Current / Active: </label>
								<select class="form-control @if($errors->first('is_current')) form-error @endif" name="is_current" id="is_current">
									<option value="yes" @if($academic_session->is_current == 'yes') selected @endif >Yes</option>
									<option value="no" @if($academic_session->is_current == 'no') selected @endif> No</option>
								</select>
							</div>						
						
							<div class="col-sm-4">
								<label>Start Date</label>
	  							<div id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
	   							<input class="form-control" type="text" name="start_date" value="{{ $academic_session->start_date }}" readonly />
	    							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								</div>
							</div>
							<div class="col-sm-4">
								<label>End Date</label>
	  							<div id="datepicker1" class="input-group date" data-date-format="mm-dd-yyyy" >
	   							<input class="form-control" type="text" name="end_date" value="{{ $academic_session->end_date }}" readonly />
	    							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								</div>
							</div>
						</div>
						<br>
						<input type="hidden" name="id" value="{{$academic_session->id}}">
						<input type="submit" value="Update" class="btn btn-primary">
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
        todayHighlight: false,
        format: 'yyyy-mm-dd'
  		});
	});
	$(function () {
  	$("#datepicker1").datepicker({ 
        autoclose: true, 
        todayHighlight: false,
        format: 'yyyy-mm-dd'
  		});
	});
	$(function() {
		$('#session_name').click(function(){
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