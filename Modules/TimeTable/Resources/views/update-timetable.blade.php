@extends('backend.submain')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
@endsection
@section('content')
<div class="form-header">Update TimeTable</div>
<div class="box-body">	
	<form action="{{route('update-timetable-post')}}" method="POST" enctype="multipart/form-data" >	
		@include('backend.partials.errors')
		<div class="form-header2">{{$subject->subject_name}}</div>
		@if($lecture_classes)
		<div class="table-responsive">									
        <table class="table table-bordered table-hover" id="time-table-table">        
			<thead>
				<tr style="background-color:#333; color:white;">
				<th>SN</th>
				<th>Room Code</th>
				<th>Teacher </th>									
				<th>Class Type</th>
				<th>Day</th>
				<th>Start Time</th>
				<th>End Time</th>
				<th>Action</th>
				</tr>
			</thead>
			<?php $i=1; ?>
			<tbody>
				@foreach($lecture_classes as $index => $class)
				<tr>
					<td>{{$i++}}</td>
					<td>{{$class->room_code}}</td>
					<td>{{$class->name}} - {{$class->teacher_id}}</td>
					<td>{{$class->type}}</td>
					<td>{{(new \App\DayAndDateTime)->returnDayName($class->day_id)}}</td>
					<td>{{(new \App\DayAndDateTime)->parseTimein12HourFormat($class->start_time)}}</td>
					<td>{{(new \App\DayAndDateTime)->parseTimein12HourFormat($class->end_time)}}</td>
					<td><input type="radio" name="lecture_class_id" id="class_id{{$class->class_id}}" value="{{$class->class_id}}"
						@if((new \Modules\TimeTable\Entities\TimeTable)->checkStudentTimeTable($class->class_id, $student_id))
						checked
						@endif
						></td>
				</tr>
				@endforeach
			</tbody>       								
        </table>
        @else
		<div class="alert alert-danger alert-dismissable">
			<h4><i class="icon fa fa-warning"></i>No any lecture classes available at the moment. Please visit the classes manager to create classes</h4>
		</div>	
		@endif        
		@if($lab_classes)
        <table class="table table-bordered table-hover" id="time-table-table">        
			<thead>
				<tr style="background-color:#333; color:white;">
				<th>SN</th>
				<th>Room Code</th>
				<th>Teacher </th>									
				<th>Class Type</th>
				<th>Day</th>
				<th>Start Time</th>
				<th>End Time</th>
				<th>Action</th>
				</tr>
			</thead>
			<?php $i=1; ?>
			<tbody>
				@foreach($lab_classes as $index => $class)
				<tr>
					<td>{{$i++}}</td>
					<td>{{$class->room_code}}</td>
					<td>{{$class->name}} - {{$class->teacher_id}}</td>
					<td>{{$class->type}}</td>
					<td>{{(new \App\DayAndDateTime)->returnDayName($class->day_id)}}</td>
					<td>{{$class->start_time}}</td>
					<td>{{$class->end_time}}</td>
					<td><input type="radio" name="lab_class_id" id="class_id{{$class->class_id}}" value="{{$class->class_id}}"
						@if((new \Modules\TimeTable\Entities\TimeTable)->checkStudentTimeTable($class->class_id, $student_id))
						checked
						@endif
						></td>
				</tr>
				@endforeach
			</tbody>       								
        </table>
        @else
		<div class="alert alert-danger alert-dismissable">
			<h4><i class="icon fa fa-warning"></i>No lab classes available at the moment. Please visit the classes manager to create classes</h4>
		</div>	
		@endif
		

        <input type="submit" value="Update Time Table" class="btn btn-success btn-flat">
        <input type="hidden" value="{{$session_id}}" name="session_id" id="session_id">
        <input type="hidden"  value="{{$subject->id}}" name="subject_id" id="subject_id">
        <input type="hidden" value="{{$student_id}}" name="student_id" id="student_id">
  		{{ csrf_field() }}
	</form>
		
</div>

@endsection
@section('custom-js')
<script type="text/javascript">
	$(document).ready(function (){
		
	});
</script>
@endsection