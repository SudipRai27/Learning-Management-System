<form action="{{route('search-for-events')}}" method="post" id="event-form">
	<div class="row">
		<div class="col-sm-5">
			<label>Event For:</label>
			@if($role == "admin")
			<select class="form-control select2" id="event_for" name="event_for">
				<option value="">Select</option>
				<option value="all" 
					@if(isset($event_for) && $event_for == 'all')
						selected 
					@endif
				>Event For All</option>
				<option value="student"
					@if(isset($event_for) && $event_for == 'student')
						selected 
					@endif
				>Event For Student</option>
				<option value="teacher"
					@if(isset($event_for) && $event_for == 'teacher')
						selected 
					@endif
				>Event for Teachers</option>
			</select>
			@elseif($role == "student")			
			<select class="form-control select2" id="event_for" name="event_for">
				<option value="student" selected>Student</option>
			</select>
			@else
			<select class="form-control select2" id="event_for" name="event_for">
				<option value="teacher" selected>Teacher</option>
			</select>
			@endif
		</div>
		<div class="col-sm-5">
			<label>Date Range:</label>
			<input type="text" name="date_range" id="daterange" class="form-control" placeholder="Please select a daterange" readonly value="{{ isset($date_range) ? $date_range : ''}}">		
		</div>
		<div class="col-sm-2">
			<br>
			<input type="submit" id="event-btn" class="btn btn-success btn-flat" value="Go">
		</div>
	</div>
	{{csrf_field()}}
</form>