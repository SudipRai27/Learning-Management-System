@extends('backend.submain')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
@endsection
@section('content')
<div class="form-header">Update Attendance</div>
<div class="form-header2">{{$subject_name}} / {{$week_name}}</div>
<div class="box-body">	
@include('backend.partials.errors')
	@if(count($students))
	<form action="{{route('update-attendance-post', $class_id)}}" method="POST">
	<div class="table-responsive">									
    	<table class="table table-bordered table-hover" id="time-table-table">        
		<thead>
			<tr style="background-color:#333; color:white;">
			<th>SN</th>
			<th>Student Name </th>
			<th>Email </th>
			<th>Remarks</th>
			<th>Action</th>
			</tr>
		</thead>
		<?php $i=1; ?>
		<tbody>
			@foreach($students as $index => $student)
			<tr>
				<?php
					$attendance = (new \Modules\Attendance\Entities\
					Attendance)->checkAttendanceByStudentId($class_id, $week_id, $student->student_id);
				?>	
				<td>{{$i++}}</td>
				<td>{{$student->name}} {{$student->uniqueID}}</td>				
				<td>{{$student->email}} </td>
				<td>
					@if($attendance)
					<input type="text" class="form-control" name="remarks[{{$student->student_id}}]" value="{{$attendance->remarks}}"></td>
					@else
					<input type="text" class="form-control" name="remarks[{{$student->student_id}}]" value=""></td>
					@endif
				<td><input type="checkbox" value ="1" name="attendance[{{$student->student_id}}]" @if($attendance) checked @endif>				 
			</tr>
			@endforeach
		</tbody>       								
    </table>
    <input type="hidden" value="{{$week_id}}" name="week_id" id="week_id">
    <input type="hidden" name="class_id" value="{{$class_id}}">
    <input type="submit" name="update-attendance" class="btn btn-warning btn-flat" value="Update Attendance">
    {{csrf_field()}}
    </form>
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