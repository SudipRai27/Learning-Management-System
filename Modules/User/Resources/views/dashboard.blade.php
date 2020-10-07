@if($role == "admin")
	@include('user::include.admin-dash')
@elseif($role == "student")
	@include('user::include.student-dash')
@elseif($role == "teacher")
	@include('user::include.teacher-dash')
@else
	@include('user::include.guest-dash')
@endif



