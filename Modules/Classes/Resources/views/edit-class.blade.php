@extends('backend.submain')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"> 
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

	.select2-container .select2-selection--single {
    height:34px !important;
	}

	.select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important; 
    	border-radius: 0px !important; 
	}

	.error-msg {
		color:red;
	}

</style>
@endsection
@section('content')
<div class="form-header"><p class="form-header">Update Class</p></div>
<form action="{{route('post-update-class', $class->id)}}" method="POST" enctype="multipart/form-data" >
	@include('backend.partials.errors')
	<div class="form-header2">Update</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label>Academic Session: </label>
				<select class="form-control select2" id="session_id" name="session_id">
				    <option value="">Select</option>
				    @foreach($academic_session as $index => $d)
				    <option value="{{$d->id}}"						        
				        @if($d->id == $class->session_id)
				        selected
				        @endif						        
				        >{{$d->session_name}} {{$d->is_current == "yes" ? '-- Current Session' : ''}}
				    </option>                               
				    @endforeach
				</select>   
				<p class="error-msg"> @if($errors->first('session_id'))  {{$errors->first('session_id')}}@endif</p> 
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label>Subject: </label>
				<select class="form-control select2 @if($errors->first('subject_id')) form-error @endif" id="subject_id" name="subject_id">
					<option value="">Select</option>
					@foreach($subjects as $index => $sub)
					<option value="{{$sub->id}}" 
						@if($class->subject_id == $sub->id)
						selected
						@endif
					>{{$sub->subject_name}} -- {{$sub->course_type}} -- {{$sub->course_title}}</option>
					@endforeach
				</select>
				<p class="error-msg"> @if($errors->first('subject_id'))  {{$errors->first('subject_id')}}@endif</p> 
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label>Class Type: </label>
				<select class="form-control select2 @if($errors->first('type')) form-error @endif" id="type" name="type">
					<option value=""> Select </option>
					<option value="lecture" @if($class->type == "lecture") selected @endif>Lecture</option>
					<option value="lab" @if($class->type == "lab") selected @endif>Lab</option>
				</select>
				<p class="error-msg"> @if($errors->first('type'))  {{$errors->first('type')}}@endif</p> 
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label>Assigned Teacher: </label>
	            <select class="form-control select2 @if($errors->first('teacher_id')) form-error @endif" name="teacher_id" id="teacher_id">
	            	@foreach($assigned_teachers as $index => $teacher)
	            	<option value="{{$teacher->id}}"
	            		@if($class->teacher_id == $teacher->id)
						selected
						@endif>
	            		{{$teacher->name}}  - {{$teacher->teacher_id}} </option>
	            	@endforeach
	            </select>
	            <p class="error-msg"> @if($errors->first('teacher_id'))  {{$errors->first('teacher_id')}}@endif</p> 
	          </div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">	
				<label>Room: </label>
				<select class="form-control select2 @if($errors->first('room_id')) form-error @endif" id="room_id" name="room_id">
					@foreach($rooms as $index => $room)
	            	<option value="{{$room->id}}"
	            		@if($room->id == $class->room_id)
	            			selected
	            		@endif
	            		>
	            		{{$room->room_code }}</option>
	            	@endforeach
				</select>
				<p class="error-msg"> @if($errors->first('room_id'))  {{$errors->first('room_id')}}@endif</p> 
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">	
				<label>Day: </label>
				<select class="form-control select2 @if($errors->first('day_id')) form-error @endif" id="day_id" name="day_id">
				@foreach($days as $index => $day)
				<option value="{{$index}}"
				@if($index == $class->day_id)
					selected
				@endif 
				>{{$day}}</option>
				@endforeach				
				</select>
				<p class="error-msg"> @if($errors->first('day_id'))  {{$errors->first('day_id')}}@endif</p> 
			</div>
		</div>		
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label>Start Time:</label>
				<input type="time" name= "start_time" id="start_time" class="form-control @if($errors->first('start_time')) form-error @endif" value="{{$class->start_time}}" />
				<p class="error-msg"> @if($errors->first('start_time'))  {{$errors->first('start_time')}}@endif</p> 
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label>End Time:</label>
	    		<input type ="time" name ="end_time" id="end_time" class="form-control demo-non-form @if($errors->first('end_time')) form-error @endif" value="{{$class->end_time}}" />	    	
	    		<p class="error-msg"> @if($errors->first('end_time'))  {{$errors->first('end_time')}}@endif</p> 	
			</div>
		</div>		
	</div>
	<input type="submit" value="Update" class="btn btn-success btn-flat">
	{{ csrf_field() }}
</form>

{{ csrf_field() }}
</form>
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-teacher-by-class-type-function.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-room-list-by-class-type-function.js')}}"></script>
<script type="text/javascript">

	const config = {
		routes: {
			getRoomByRoomTypeRoute: '{{route("ajax-get-room-by-type")}}', 
			getAssignedTeacherByClassType: '{{route("ajax-get-assigned-teacher-by-class-type")}}'
		}
	}

	$(document).ready(function (){

		$('.select2').select2();

		
		$('#subject_id').on('change', function(){
			let subject_id = $(this).val(); 
			let type = $('#type').val();
			let session_id = $('#session_id').val();
			if(type)
			{
				updateTeacherListByClassType(session_id, subject_id, type);	
			}
		});
				
   		$('#type').on('change', function() {   			
   			type = $(this).val();    	
   			subject_id = $('#subject_id').val();	
   			session_id = 	$('#session_id').val();
   			updateTeacherListByClassType(session_id, subject_id, type);
   			updateRoomListByTypeClassType(type);
   		});

   		$('#session_id').on('change', function() {
			let subject_id = $('#subject_id').val(); 
			let type = $('#type').val();
			let session_id = $('#session_id').val();
			if(type && subject_id)
			{
				updateTeacherListByClassType(session_id, subject_id, type);	
			}

		});


		/* form elements */
        $('#type').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#teacher_id').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#day_id').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 

        $('#subject_id').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 

        $('#room_id').change(function(){
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
        /* form elements */
	});
</script>


@endsection