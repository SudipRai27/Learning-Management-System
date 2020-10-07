<div class="col-sm-6">
	<label>Session:</label>
	<select class="form-control select2" name="session_id" id="session_id">
		<option value="0">Select</option>
		@foreach($academic_session as $index => $session)
		<option value="{{$session->id}}"
			@if($session->id == $selected_session_id)
				selected
			@endif
			>{{$session->session_name}} {{$session->is_current == "yes" ? "-- Current Session --" : ''}}</option>
		@endforeach
	</select>				

</div>
<div class="col-sm-6">
	<label>Enrolled Students:</label>
	<select class="form-control select2" name="student_id" id="student_id">
		<option value="0">Select Session First</option>
	</select>
	<input type = "hidden" value="{{$selected_student_id}}" id="selected_student_id">
</div>