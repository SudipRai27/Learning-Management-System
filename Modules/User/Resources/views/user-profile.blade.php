@extends('backend.main')
<style type="text/css">
	.profile-heading{
		text-align: center;
		font-size: 2rem;
		background-color: #b052ae;
		padding: 1.5rem;
		color:white;
		width: 70%;
		margin: 1rem auto 2rem;

	}
	.profile {
		display: grid;
		grid-template-columns: 1fr 2fr;
		justify-content: flex-start;
		align-items: center;
		gap: 2rem;		
		padding: 2rem;
		border: 2px solid rgba(0,0,0,0.1);
		box-shadow: 0px 10px 11px -1px rgba(1,2,3,0.3), -2px -2px 22px 1px rgba(0,0,0,0.5);
		
	}

	img {
		width: 100%;
	}
	

	.change-password {
		text-align: center;		
	}

	.role {
		background-color: #5f5f9e;
		padding: 0.5rem 1rem;
		margin-right: 0.1rem;
		color:white;
	}
	
	.password-error-msg {
		position:relative;
		color: red;
		background: #f4f4f4;
		text-align: left;
		margin-left: 13px;

	}

	.password-error-msg:before {
		position: absolute;
	  	left: -15px;
	    content: "✖";
	}

	.password-success-msg {
		color: green;
		background: #f4f4f4;
		text-align: left;
		margin-left: 13px;
		
	}

	.password-success-msg:before {
		position: absolute;
	  	left: 12px;
	   	content: "✔";
	}
	.buttons {
		display: block;
		width: 80%;
		margin: auto;
		margin-top: 1rem;
		margin-bottom: 1rem;
		text-align: center;
	}	

	@media only screen and (max-width: 1000px) {
		.profile {
			grid-template-columns: 1fr;
		}

		.profile-img {			
			text-align: center;
		}
	}

</style>
@section('content')
<div class="box-body">
	@include('backend.partials.errors')
	<h2 class="profile-heading">Profile Details</h2>
	<div class="profile">
		<div class="profile-img">
			@if($current_user->photo)
				@if($role == "admin")
					<img src="{{url('/modules/admin/resources/assets/images').'/'. $current_user->photo}}" class="img-circle">              
				@elseif($role == "teacher")
					<img src="{{url('/modules/teacher/resources/assets/images').'/'. $current_user->photo}}" class="img-circle">             
				@elseif($role == "student")
					<img src="{{url('/modules/student/resources/assets/images').'/'. $current_user->photo}}" class="img-circle">             				
				@endif
				<div class="buttons">
					<form action="{{route('remove-profile-picture')}}" method="POST">
						<input type="submit" name="" value="Remove my profile picture" class="btn btn-danger btn-flat">
						<input type="hidden" name="user_id" value="{{$current_user->id}}">
                        <input type="hidden" name="role_name" value="{{$role}}">
                        {{csrf_field()}}
					</form>
				</div>
			@else
				<img src="{{ URL::to('public/images/default-user.png')}}" class="img-circle" > 
				<div class="buttons">
					<a class="btn btn-warning btn-flat"  data-toggle="modal" data-target="#uploadProilePicture{{$current_user->id}}" data-title="Upload" data-message="Upload">
						Upload a new profile picture                        
                    </a>            
                    @include('user::modal.change-profile-picture')   
				</div>
			    
			@endif
			
		</div>
		<div class="profile-content">
			<table class="table table-hover">
				<tbody>					
					<tr>
						<td>Full Name: </td>
						<td>{{$current_user->name}}</td>
					</tr>
					<tr>
						<td>Email: </td>
						<td>{{$current_user->email}}</td>
					</tr>
					<tr>
						<td>Address: </td>
						<td>{{$current_user->address}}</td>
					</tr>
					<tr>
						<td>DOB: </td>
						<td>{{date('Y-M-d',strtotime($current_user->dob))}}</td>
					</tr>
					<tr>
						<td>Current Role </td>
						<td>
							@foreach($role_details as $index => $role)
								<span class="role">{{$role->role_name}}</span>
							@endforeach
						</td>
					</tr>
					<tr>
						<td>Phone: </td>
						<td>{{$current_user->phone}}</td>
					</tr>
					<tr>
						<td>Emergeny Contact Name: </td>
						<td>{{$current_user->emergency_contact_name}}</td>
					</tr>
					<tr>
						<td>Emergeny Contact Number: </td>
						<td>{{$current_user->emergency_contact_number}}</td>
					</tr>
					<tr>
						<td>Registered on: </td>
						<td>{{date('Y-M-d',strtotime($current_user->created_at))}}</td>
					</tr>					
				</tbody>
			</table>
			<div class="change-password">
				<a class="btn btn-primary btn-flat"  data-toggle="modal" data-target="#changePassword{{$current_user->id}}" data-title="Change Password" data-message="Are you sure you want to change the password ?">
				Change Password
				</a> 			
				@include('user::modal.change-password')
				
			</div>			
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript">
	const password = document.getElementById("password");
	const password_label = document.getElementById("password_label");
	const confirm_password = document.getElementById("confirm_password");
	const confirm_password_label = document.getElementById("confirm_password_label");
	
	const update_button = document.getElementById("update-password");
	document.getElementById("update-password").setAttribute("disabled", "disabled");

	password.onkeyup = function() {
		if(checkPassword(password.value.trim()))
		{
			password_label.innerHTML = "<br><p class = 'password-success-msg'>Password has minimum eight characters, at least one letter and one number</p>";
			if(password == confirm_password)
			{	
				document.getElementById("update-password").removeAttribute("disabled");
			}
			else
			{
				document.getElementById("update-password").setAttribute("disabled", "disabled");
				confirm_password_label.innerHTML ="<br><p class = 'password-error-msg'>Password do not match</p>";
			}
		}
		else
		{
			password_label.innerHTML = "<br><p class = 'password-error-msg'>Password requires minimum eight characters, at least one letter and one number</p>";
			confirm_password_label.innerHTML = "";
			document.getElementById("update-password").setAttribute("disabled", "disabled");
		}
	}


 	function checkPassword(str)
  	{
    	var re = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
    	return re.test(str);
  	}

	confirm_password.onkeyup = function () {

		confirm_password_label.innerHTML ="<br><p class = 'password-error-msg'>Password do not match</p>";
		if(password.value.trim() == confirm_password.value.trim())
		{
			if(checkPassword(password.value.trim()) && checkPassword(confirm_password.value.trim()))
			{
				confirm_password_label.innerHTML = "<br><p class = 'password-success-msg'>Password matches and meets all the requirements.</p>";			
				document.getElementById("update-password").removeAttribute("disabled");	
		
			}
			else
			{
				password_label.innerHTML = "<br><p class = 'password-error-msg'>Password and requires minimum eight characters, at least one letter and one number</p>";
				confirm_password_label.innerHTML = '';

			}			
			
		}
		else
		{
			document.getElementById("update-password").setAttribute("disabled", "disabled");
		}
	}

	update_button.addEventListener("click", (e) => {
		e.preventDefault();
		if(!checkPassword(password.value.trim()))
		{
			toastr.error('Password does not meet the requirements');
			return;
		}

		if(!(password.value.trim() == confirm_password.value.trim()))
		{
			toastr.error('Password and confirm password does not match');
			return;
		}		
		document.getElementById('change-password-form').submit();

	})

</script>
@endsection