@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/fileinput.css') }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/sms/assets/css/theme.css') }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
<style type="text/css">
	.form-header2 {
        background-color: orange;
        margin:10px 0px;
        height:35px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        padding: 6px;
	}

	.error-badge {
		display: inline-block;
		margin: 1rem 0;
	}
</style>
@endsection
@section('content')
<h4><b>Event Manager</b></h4>		
@include('event::tabs')
<div class="form-header"><p class="form-header">Create Event</p></div>
<form action="{{route('create-event-post')}}" method="POST" enctype="multipart/form-data">
	<div class="box-body">
		@include('backend.partials.errors')
		<div class="form-header2">Create</div>
		<div class="form-group">
			<label>Event Title: </label>
			<input type="text" name="event_title" id ="event_title" class="form-control" value="{{ Input::old('event_title')}}" placeholder="Event Title">
			@if($errors->first('event_title'))  
			<span class="error-badge">{{$errors->first('event_title')}} </span>
			@endif
			</div>
		<div class="form-group">
			<b>Description:</b>
			<textarea id="mytextarea" name="description" class="form-control" placeholder="Description" rows="20">
      			{{ Input::old('description')}}
    		</textarea>
    		@if($errors->first('description'))
    		<span class="error-badge">{{$errors->first('description')}}</span>
    		@endif
		</div>		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label>Start Time:</label>
					<input type="time" name= "start_time" id="start_time" class="form-control" value="{{Input::old('start_time')}}" />
					@if($errors->first('start_time'))  
					<span class="error-badge"> {{$errors->first('start_time')}}</span>
					@endif
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label>End Time:</label>
		    		<input type ="time" name ="end_time" id="end_time" class="form-control" value="{{Input::old('end_time')}}" />	    
		    		@if($errors->first('end_time'))  
		    		<span class="error-badge">{{$errors->first('end_time')}}</span>
		    		@endif
				</div>
			</div>		
		</div>		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label>Start Date:</label>
					<input type="date" name= "start_date" id="start_date" class="form-control" value="{{Input::old('start_date')}}" />
					@if($errors->first('start_date'))  
					<span class="error-badge"> {{$errors->first('start_date')}}</span>
					@endif 
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label>End Date:</label>
		    		<input type ="date" name ="end_date" id="end_date" class="form-control" value="{{Input::old('end_date')}}" />	    
		    		@if($errors->first('end_date'))  
		    		<span class="error-badge">{{$errors->first('end_date')}}</span>
		    		@endif
				</div>
			</div>		
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">	
					<label>Location:</label>
		        	<input id="location" class="form-control" type="text" placeholder="Enter a location or search for a location" style="margin: 0px auto;" name="location" value="{{Input::old('location')}}">
		        	@if($errors->first('location'))  
		        	<span class="error-badge">{{$errors->first('location')}}</span>
		        	@endif 
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label>Featured Image (*Multiple Allowed : jpg,jpeg,png,svg,gif)</label>
					<input id="kv-explorer" type="file" name="featured_image[]" multiple>
       		    	@if($errors->first('featured_image'))  
					<span class="error-badge"> {{$errors->first('featured_image')}}</span>
					@endif 					
				</div>
			</div>
		</div>
		<div class="form-group">
			<label>Event For: </label>
			<input type="checkbox" name="event_for[]" value="student" 
			@if(Input::old('event_for.0') == "student" || Input::old('event_for.1') == "student")
			checked @endif> Student
			<input type="checkbox" name="event_for[]" value="teacher" @if(Input::old('event_for.0') == "teacher" || Input::old('event_for.1') == "teacher")
			checked @endif> Teacher
			@if($errors->first('event_for'))  
			<span class="error-badge"> 
			{{$errors->first('event_for')}}
			</span>
			@endif 
		</div>			
		<input type="submit" value="Create" class="btn btn-success btn-flat">
		<input type="reset" name="" value="Reset Form" class="btn btn-danger btn-flat">
	</div>
{{ csrf_field() }}
</form>
@endsection
@section('custom-js')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('public/sms/assets/js/fileinput.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/sms/assets/js/theme.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAv95omW2gJppOIwwOPDwft0Ob-D-qANMs&libraries=places"></script>
<script type="text/javascript">
	$(document).ready(function (){

		google.maps.event.addDomListener(window, 'load', initialize);
        function initialize() {
            var input = document.getElementById('location');
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();                
            });
        }

		$('#event_title').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#mytextarea').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#event_for').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 

        $('#location').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 

        $('#start_time').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#end_time').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 

        $('#start_date').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 

        $('#featured_image').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 

        $('#end_date').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
	});


</script>
<script src="https://cdn.tiny.cloud/1/cihbjlc54e3wtdrzdj8590rct8sm2ih12lrpjxogi98neu98/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
	tinymce.init({
      selector: '#mytextarea',
      plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
      toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
      toolbar_mode: 'floating',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
    });
</script>
@endsection