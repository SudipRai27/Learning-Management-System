@if(isset($subjects))
<label>Subject: </label>
<select class="form-control select2 @if($errors->first('subject_id')) form-error @endif" id="subject_id" name="subject_id">
	<option value="">Select</option>
	@foreach($subjects as $index => $sub)
	<option value="{{$sub->id}}" 
		@if($selected_subject_id == $sub->id)
		selected
		@endif
	>{{$sub->subject_name}} -- {{$sub->course_type}} -- {{$sub->course_title}}</option>
	@endforeach
</select>
@endif

