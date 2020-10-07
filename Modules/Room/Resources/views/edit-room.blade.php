@extends('backend.submain')
@section('custom-css')
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
</style>
@endsection
@section('content')
<div class="form-header"><p class="form-header">Edit Room</p></div>
<form action="{{route('edit-room-post', $room->id)}}" method="POST" enctype="multipart/form-data" >
	<div class="box-body">
		@include('backend.partials.errors')	
		<div class="form-header2">Update</div>	
		<div class="form-group">
		<label>Room Code: </label>
		<input type="number" name="room_code" id ="room_code" class="form-control @if($errors->first('room_code')) form-error @endif" value="{{ $room->room_code}}" placeholder="Room Code">
		</div>
		<div class="form-group">
		<b>Description:</b> <textarea type="text" name="description" class="form-control @if($errors->first('description')) form-error @endif" placeholder="Description" rows="6" id="description">{{ $room->description }}</textarea>
		</div>
		<div class="form-group">
		<label>Type: </label>
		<select class="form-control @if($errors->first('room_type')) form-error @endif" name="room_type" id="room_type">
			<option value="">Select</option>
			<option value="lecture_room" @if($room->room_type == 'lecture_room') selected @endif>Lecture Room</option>
			<option value="lab_room" @if($room->room_type == 'lab_room') selected @endif>Lab Room</option>
		</select>
		<br>
		<input type="submit" value="Edit" class="btn btn-success btn-flat">		
	</div>
{{ csrf_field() }}
</form>
@endsection
@section('custom-js')
<script type="text/javascript">
	$(document).ready(function (){
		$('#room_code').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#description').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#room_type').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
	});
</script>
@endsection