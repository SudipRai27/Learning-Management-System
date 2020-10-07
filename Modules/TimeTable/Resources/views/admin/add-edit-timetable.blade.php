<div class="row">
	<div class="col-sm-6">
		@include('backend.partials.academic-session-partial')
	</div>
	<label>Enrolled Students: </label>
	<div class="col-sm-6">
		<select class="form-control select2" name="student_id" id="student_id">
			<option value="">Select Academic Session First</option>
		</select>
	</div>
</div>
<input type="hidden" value="{{$selected_student_id}}" id="selected_student_id">
<div class="subject-list">
	@if(!is_null($enrolled_subjects))
		@if(count($enrolled_subjects))
		<header class="header">Displaying enrolled subjects</header>
		<div class="table-responsive">
			<table class="table table-bordered table-hover" id="timetable-table">        
				<thead>
					<tr style="background-color:#333; color:white;">
					<th>SN</th>
					<th>Subject Name </th>
					<th>Credit Points </th>
					<th>Is Graded </th>
					<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					$i = 1;
				?>
				@foreach($enrolled_subjects as $index => $subject)
					<tr>
						<td>{{$i++}}</td>
						<td>{{$subject->subject_name}}</td>
						<td>{{$subject->credit_points}}</td>
						<td>{{$subject->is_graded}}</td>
						<td><a href = "{{route('update-timetable', [$selected_session_id, $subject->id, $selected_student_id])}}" data-lity><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Update"> Update TimeTable<i class="fa fa-fw fa-edit"></i></button></a>	</td>
					</tr>
				@endforeach
				</tbody>       								
			</table>
		</div>
		@else
			<div class="alert alert-danger alert-dismissable">
				<h4><i class="icon fa fa-warning"></i>You have not enrolled in any subject in this session</h4>
			</div>
		@endif
	@else
	<div class="alert alert-warning alert-dismissable">
		<h4><i class="icon fa fa-warning"></i>Please select session and student</h4>
	</div>	    	
	@endif
</div>
