@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Exam Manager</b></h4>		
		<div class="box"> 
			<div class="box-body">								
				@include('exam::tabs')				
				<div class="form-header"><p class="form-header">Create Exam</p></div>	
				<div class="form-header2">Add</div>
				@include('backend.partials.errors')
				<form action="{{route('create-exam-post')}}" enctype="multipart/form-data" method="POST">
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label>Academic Session: </label>
							<select class="form-control select2" id="session_id" name="session_id">				<option value="">Select</option>
							    @foreach($academic_session as $index => $d)
							    <option value="{{$d->id}}"						        
							        @if($d->id == Input::old('session_id'))
							        selected
							        @endif						        
							        >{{$d->session_name}} {{$d->is_current == "yes" ? '-- Current Session' : ''}}
							    </option>                               
							    @endforeach							    							    
							</select>  					
							<p class="error-msg"> @if($errors->first('session_id'))  {{$errors->first('session_id')}}@endif</p> 
						</div>
					</div>					
				</div>
				<div class="form-group">
					<label>Exam Name: </label>
                    <input type="text" name="exam_name" id = "exam_name" class="form-control @if($errors->first('exam_name')) form-error @endif" placeholder="Exam Name" value="{{Input::old('exam_name')}}">    
                    <p class="error-msg"> @if($errors->first('exam_name'))  {{$errors->first('exam_name')}}@endif</p>                     
				</div>				
				<div class="row">					
					<div class="col-sm-6">
						<div class="form-group">
							<label>Start Date: </label>
							<div class='form-group'>
			                    <input type='date' class="form-control @if($errors->first('start_date')) form-error @endif" name="start_date" id="start_date" placeholder="Please select date from the icon on the right" value="{{Input::old('start_date')}}">
			                    <p class="error-msg"> @if($errors->first('start_date'))  {{$errors->first('start_date')}}@endif</p>  					
			                </div>			          
			            </div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
		                    <label>End Date: </label>
			                    <input type='date' class="form-control @if($errors->first('end_date')) form-error @endif" name="end_date" id="end_date" placeholder="Please select date from the icon on the right" value="{{Input::old('end_date')}}">
			                    <p class="error-msg"> @if($errors->first('end_date'))  {{$errors->first('end_date')}}@endif</p>  
						</div>								
					</div>
				</div>				
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label>Full Marks: </label>
		                    <input type="number" name="marks" id = "marks" class="form-control @if($errors->first('marks')) form-error @endif" placeholder="Marks" value="{{Input::old('marks')}}" min="1" max="100">
		                    <p class="error-msg"> @if($errors->first('marks'))  {{$errors->first('marks')}}@endif</p> 		                
						</div>
					</div>
				</div>
					<input type="submit" class="btn btn-success btn-flat" value="Create">
					<input type="reset" class="btn btn-danger btn-flat" value="Reset Form">		{{ csrf_field() }}
				</form>															
			</div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<script type="text/javascript">
	$(document).ready(function (){
		$('.select2').select2();
		$('#datetimepicker1').datetimepicker();

		$('#start_date').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
		$('#exam_name').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#marks').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#end_date').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
	});
</script>
	
@endsection