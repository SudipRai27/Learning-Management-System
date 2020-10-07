<div class="row">
	<div class="col-sm-6">
		@include('backend.partials.academic-session-partial')
	</div>
	<div class="col-sm-6">
		@include('backend.partials.normal-subject-partial')
	</div>
</div>						
<div class="class-list">		
	@if(!is_null($class_list))
		@if(count($class_list))
		<div class="table-responsive">
			<table class="table table-bordered table-hover" id="class-table">     
				<?php $i = 1; ?>
				<thead>
					<tr style="background-color:#333; color:white;">
						<th>SN</th>
						<th>Room Code</th>							
						<th>Teacher Name</th>
						<th>Class Type</th>
						<th>Day</th>
						<th>Start Time</th>
						<th>End Time</th>									
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($class_list as $index => $record)
					<tr>
						<td>{{$i++}}</td>
						<td>{{$record->room_code}}</td>
						<td>{{$record->name}} {{$record->teacher_id}}</td>
						<td>{{$record->type}}</td>
						<td> {{ (new \App\DayAndDateTime)->returnDayName($record->day_id) }}
						</td>
						<td>{{ (new \App\DayAndDateTime)->parseTimein12HourFormat($record->start_time) }}</td>
						<td>{{ (new \App\DayAndDateTime)->parseTimein12HourFormat($record->end_time) }}</td>
						<td>											
							<a class="btn btn-success btn-flat"  data-toggle="modal" data-target="#updateAttendance{{$record->class_id}}" data-title="View Student" data-message="View Student">
								<i class="glyphicon glyphicon-file"></i> 
								Update Attendance
									</a> 			
							@include('attendance::modal.modal-attendance-week-view')							
						</td>
					</tr>
					@endforeach								
				</tbody>       								
		    </table>
		</div>		
		@else
		<br>
		<div class="alert alert-danger alert-dismissable">
	        <h4><i class="icon fa fa-warning"></i>Classes not found</h4>
	    </div>  
		@endif
	@else
		<br>
		<div class="alert alert-warning alert-dismissable">
			<h4><i class="icon fa fa-warning"></i>Please Select Session And Subject </h4>
		</div>	
	@endif
</div>