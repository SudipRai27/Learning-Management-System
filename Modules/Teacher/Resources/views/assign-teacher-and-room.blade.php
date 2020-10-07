@extends('backend.submain')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"> 
<link rel="stylesheet" href="{{ asset('public/sms/assets/css/mobiscroll.jquery.min.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
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

</style>
@endsection
@section('content')
<div class="form-header"><p class="form-header">Assign Lecturer and Tutors </p></div>
<div class="form-header2">Assign</div>
@include('backend.partials.errors')
<form action="{{route('post-assign-teacher-and-room', $subject->id)}}" method="POST" enctype="multipart/form-data">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label>Class Type: </label>
				<select class="form-control select2 @if($errors->first('type')) form-error @endif" id="type" name="type">
					<option value=""> Select </option>
					<option value="lecture" @if(Input::old('type') == "lecture") selected @endif>Lecture</option>
					<option value="lab" @if(Input::old('type') == "lab") selected @endif>Lab</option>
				</select>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label>Teacher: </label>
	            <select class="form-control select2 @if($errors->first('teacher_id')) form-error @endif" name="teacher_id" id="teacher_id">
	            	<option value="">Select Class Type First</option>
	            </select>
	          </div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">	
				<label>Room: </label>
				<select class="form-control select2 @if($errors->first('room_id')) form-error @endif" id="room_id" name="room_id">
				<option value="">Select Class Type First</option>
				</select>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">	
				<label>Day: </label>
				<select class="form-control select2 @if($errors->first('day_id')) form-error @endif" id="day_id" name="day_id">
				@foreach($days as $index => $day)
				<option value="{{$index}}"
				@if($index == Input::old('day_id'))
					selected
				@endif 
				>{{$day}}</option>
				@endforeach				
				</select>
			</div>
		</div>		
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label>Start Time:</label>
	    		<input name= "start_time" id="start_time" class="form-control demo-non-form @if($errors->first('start_time')) form-error @endif" value="{{Input::old('start_time')}}" />
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label>End Time:</label>
	    		<input name ="end_time" id="end_time" class="form-control demo-non-form @if($errors->first('end_time')) form-error @endif" value="{{Input::old('end_time')}}" />
			</div>
		</div>
	</div>
	<input type="text" name="subject_id" value="{{$subject->id}}" id="subject_id">
	<input type="text" name="session_id" value="{{$current_academic_session->id}}" id="session_id">	
	<input type="submit" value="Assign" class="btn btn-success btn-flat">
	{{ csrf_field() }}
</form>
<input type="text" name="" id="old_teacher_id" value="{{Input::old('teacher_id')}}">
<input type="text" name="" id="old_room_id" value="{{Input::old('room_id')}}">



@endsection
@section('custom-js')
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-teacher-by-type-function.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{asset('public/sms/assets/js/mobiscroll.jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
	const config = {
        routes: {

            getTeacherByTypeAutoCompleteRoute: '{{route("get-teacher-by-type-autocomplete")}}',
            checkAssignedTeacherRoute: '{{route("check-assigned-teacher")}}'
        }
    };    

	$(document).ready(function (){

		$('.select2').select2();

		if($('#type').val())
		{
			updateTeacherListByClassType($('#type').val());
   			updateRoomListByTypeClassType($('#type').val());
		}
				
   		$('#type').on('change', function() {
   			const type = $(this).val();    			
   			updateTeacherListByClassType(type);
   			updateRoomListByTypeClassType(type);
   		})


   		function updateTeacherListByClassType(type)
   		{
   			$('#teacher_id').html("<option value=''>Loading</option>");
   			$.ajax({
   				url : '{{route("ajax-get-teacher-by-class-type")}}',
   				type: 'GET', 
   				data: {
   					type,
   					selected_teacher_id: $('#old_teacher_id').val()
   				}, 
   				success:function (data) {
   					$('#teacher_id').html(data);
   				}, 	
   				error: function (jqXhr, textstatus) {
   					toastr.error(textstatus);
   				}
   			});
   		}

   		function updateRoomListByTypeClassType(type)
   		{
   			$('#room_id').html("<option value=''>Loading</option>");
   			let room_type = '';
   			if(type == "lecture")
   			{
   				room_type = 'lecture_room';
   			}
   			else
   			{
   				room_type = 'lab_room';
   			}
   			$.ajax({
   				url : '{{route("ajax-get-room-by-type")}}',
   				type: 'GET', 
   				data: {
   					room_type, 
   					selected_room_id: $('#old_room_id').val()
   				}, 
   				success:function (data) {   					
   					$('#room_id').html(data);
   				}, 	
   				error: function (jqXhr, textstatus) {
   					toastr.error(textstatus);
   				}
   			})
   		}

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

<script>
	/*time*/
    mobiscroll.settings = {
        lang: 'en',                           // Specify language like: lang: 'pl' or omit setting to use default
        theme: 'ios',                         // Specify theme like: theme: 'ios' or omit setting to use default
        themeVariant: 'light',            // More info about themeVariant: https://docs.mobiscroll.com/4-10-6/datetime#opt-themeVariant
        display: 'bubble'                     // Specify display mode like: display: 'bottom' or omit setting to use default
    };
    $(function () {    
        var now = new Date();  
        // Mobiscroll Date & Time initialization        
        // Mobiscroll Date & Time initialization
        $('#start_time').mobiscroll().time({
            onInit: function (event, inst) {  // More info about onInit: https://docs.mobiscroll.com/4-10-6/datetime#event-onInit
                inst.setVal(now, false);
            }
        });
        $('#end_time').mobiscroll().time({
            onInit: function (event, inst) {  // More info about onInit: https://docs.mobiscroll.com/4-10-6/datetime#event-onInit
                inst.setVal(now, false);
            }
        });
    });
    /* time */
</script>
@endsection