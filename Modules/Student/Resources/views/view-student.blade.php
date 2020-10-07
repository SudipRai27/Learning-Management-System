@extends('backend.main')
@section('content')
<div class="tab-pane active" id="tab_1">
	<div class="row" style="margin-bottom:15px">
	    <div class="col-sm-3 col-xs-2" >
    	  <a  href="#" onclick="history.go(-1);" class="btn btn-danger btn-flat"><i class="fa fa-fw fa-arrow-left "></i> Go Back</a>
    	</div><!-- row ends -->
    	<div class="col-sm-3 col-sm-offset-6 col-xs-offest-1 col-xs-9" >
    		<div class="btn-group pull-right">	
				<button class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" type="button" aria-expanded="false">
					<i class="fa fa-fw fa-cog"></i>				
				</button>
				<ul class="dropdown-menu" role="menu">
					<li>
						<a href="#" data-toggle="modal" data-target="#details">Edit details</a>
					</li>
					<li>
					<a href="#" data-toggle="modal" data-target="#password">Change password</a>
					</li>
				</ul>
			</div>
    	</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<div class="profile-image">			
				@if($student->photo)
				<img class="img-responsive" src = "{{url('/modules/student/resources/assets/images').'/'. $student->photo}}" >
				@else
				<img class="img-responsive" src = "" >	
				@endif		
			</div>
			<div class="profile-detail">
				<div class="main-head" style="text-align:center">				
				</div>
				<div class="second-head" style="text-align:center">
					<span style="color:#333">Student ID: {{$student->student_id}}</span> 
				</div>
				<ul>
					<li>
						<label> Full Name: </label><span class="text-green"> {{$student->name}}</span>
					</li>
					<li>
						<label>Email:</label> <span class="text-green"> {{$student->email}}</span>
					</li>
					<li>
						<label>DOB:</label> <span class="text-green">{{$student->dob}}</span>
					</li>				
					<li>
						<label>Address:</label> <span class="text-green"> {{$student->address}} </span>
					</li>
					<li>
						<label>Phone Number:</label> <span class="text-green">  {{$student->phone}}</span>
					</li>
					<li>
						<label>Emergency Contact Name: </label> <span class="text-green"> {{$student->emergency_contact_name}} </span>
					</li>
					<li>
						<label>Emergency Contact Information: <span class="text-green"> {{$student->emergency_contact_number}} </span>
					</li>									
					<li>
						<label>Current Program Type / Course Type: <span class="text-green"> {{ Modules\Course\Entities\CourseType::where('id', $student->current_course_type_id)->pluck('course_type')[0]}} </span>
					</li>				
					<li>
						<label>Current Course: <span class="text-green"> {{ Modules\Course\Entities\Course::where('id', $student->current_course_id)->pluck('course_title')[0]}}  </span>
					</li>				
				</ul>
			</div>
		</div>
		<div class="col-sm-8">
			<div class=" profile-menu" style="margin-bottom:15px">
				<a class="btn btn-profile btn-info btn-flat" id="dynamicDocument">
					<i class="fa fa-file-o"></i>
					Docs
				</a>	    			
				<a class="btn btn-profile btn-primary btn-flat" id="dynamicExam">
					<i class="fa fa-graduation-cap"></i>
					Exam Reports
				</a>
				<a class="btn btn-profile bg-purple btn-flat" id="dynamicLibrary">
					<i class="fa fa-book"></i>
					Library History
				</a>
				<a class="btn btn-profile btn-success btn-flat" id="dynamicExtraActivities">
					<i class="fa fa-rocket"></i>
					Extra Activities
				</a>
				
				<a class="btn btn-profile btn-warning btn-flat" id="dynamicPayment">
					<i class="fa fa-dollar"></i>
					Payments
				</a>
				<a class="btn btn-profile btn-danger btn-flat" id="dynamicAttendance">
					<i class="fa fa-bar-chart"></i>
					Attendance
				</a>									
    		</div>
			<div id = "dynamicContent">
			</div>
		</div>
	</div>		
</div>
@endsection
