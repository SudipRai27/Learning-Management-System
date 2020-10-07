<div class="col-sm-6">
	<label>Academic Session: </label>
	<select class="form-control select2" id="session_id" name="session_id">
	    @if($role=="admin")
	    <option value="">Select</option>
	    @foreach($academic_session as $index => $d)
	    <option value="{{$d->id}}"						        
	        @if($d->id == $selected_session_id)
	        selected
	        @endif						        
	        >{{$d->session_name}} {{$d->is_current == "yes" ? '-- Current Session' : ''}}
	    </option>                               
	    @endforeach
	    @else
	    <option value="{{$current_academic_session->id}}">{{$current_academic_session->session_name}}</option>
	    @endif		
	</select>    
</div>
<div class="col-sm-6">
    <label>Subject: </label><br>
	<select class="form-control select2" id="subject_id" name="subject_id">
		<option value="">Select</option>
		@if($role == "admin")
		@foreach($subjects as $index => $sub)
		<option value="{{$sub->id}}" 
			@if($selected_subject_id == $sub->id)
			selected
			@endif
		>{{$sub->subject_name}} -- {{$sub->course_type}} -- {{$sub->course_title}}</option>
		@endforeach
		@else
		@foreach($teacher_subjects as $index => $sub)
		<option value="{{$sub['id']}}" 
			@if($selected_subject_id == $sub['id'])
			selected
			@endif
		>{{$sub['subject_name']}} -- {{$sub['course_type']}} -- {{$sub['course_title']}}</option>
		@endforeach
		@endif
	</select>
</div>