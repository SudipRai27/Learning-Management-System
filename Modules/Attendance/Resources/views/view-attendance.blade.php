@extends('backend.submain')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
<style type="text/css">
	#class-details {
		display:flex;
		width: 100%;
		height: 22px;
		justify-content: space-around;
		align-items: center;
		flex-wrap: wrap;
	}
	#class-details div {
		font-weight: bold;
		font-size: 15px;
	}
</style>
@endsection
@section('content')
<div class="form-header">View Attendance</div>
<div class="form-header2">{{$session_name}} / {{$subject_name}} </div>
<div id="class-details">
	<div>Room Code: {{(new \Modules\Room\Entities\Room)->getRoomNameFromId($class->room_id)}}</div>
	<div>
		Day: {{ (new \App\DayAndDateTime)->returnDayName($class->day_id) }}
	</div>
	<div>
		Time: {{ (new \App\DayAndDateTime)->parseTimein12HourFormat($class->start_time) }} - 
		{{ (new \App\DayAndDateTime)->parseTimein12HourFormat($class->end_time) }}
	</div>
</div>
<div class="box-body">	
@include('backend.partials.errors')
	@if(count($student_attendance))
	<div class="table-responsive">									
    	<table class="table table-bordered table-hover" id="time-table-table">        
		<thead>
			<tr style="background-color:#333; color:white;">
			<th>SN</th>
			<th>Student Name / Student ID</th>
			@foreach($attendance_weeks as $index => $week)
			<th>{{$week}} </th>
			@endforeach
			</tr>
		</thead>
			<?php $i = 1; ?>
			<tr>
				@foreach($student_attendance as $index => $record)
				<tr>
					<td>{{$i++}}</td>
					<td>{{$record->name}}</td>
					@foreach($attendance_weeks as $index => $week)
					<td>
						@if(in_array($index, $record->attendance_weeks))
						<input type="checkbox" name="attendance" id="attendance" checked>
						@else
						<input type="checkbox" name="attendance" id="attendance">
						@endif
					</td>
					@endforeach
				</tr>
				@endforeach
			</tr>
		<tbody>
			
		</tbody>       								
    </table>
    @else
	<div class="alert alert-danger alert-dismissable">
		<h4><i class="icon fa fa-warning"></i>No any students have their timetable in this class</h4>
	</div>
	@endif
</div>
@endsection
@section('custom-js')
<script type="text/javascript">
	$(document).ready(function (){
		
	});
</script>
@endsection