<div class="col-sm-6">
	<label>Academic Session: </label>
	<select class="form-control select2" id="session_id" name="session_id">	    
	    <option value="">Select</option>
	    @foreach($academic_session as $index => $d)
	    <option value="{{$d->id}}"						        
	        @if($d->id== $selected_session_id)
	        selected
	        @endif						        
	        >{{$d->session_name}} {{$d->is_current == "yes" ? '-- Current Session' : ''}}
	    </option>                               
	    @endforeach	    	
	</select>    
</div>
@if($role=="admin")
<div class="col-sm-6">
    <label>Subject: </label><br>
	<select class="form-control select2" id="subject_id" name="subject_id">
		<option value="">Select</option>
		@foreach($subjects as $index => $sub)
		<option value="{{$sub->id}}" 
			@if($selected_subject_id == $sub->id)
			selected
			@endif
		>{{$sub->subject_name}} -- {{$sub->course_type}} -- {{$sub->course_title}}</option>
		@endforeach		
	</select>
</div>
@elseif($role=="teacher")
<div class="col-sm-6">
    <label>Subject: </label><br>
	<select class="form-control select2" id="subject_id" name="subject_id">
		<option value="">Select</option>		
	</select>
</div>
@else

@endif