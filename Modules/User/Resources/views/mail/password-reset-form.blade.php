<!DOCTYPE html>
<html>
<head>
	<title>Reset Password</title>
	<link href="{{asset('public/sms/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />       
	<script src="{{asset('public/sms/plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{asset('public/sms/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>    
    <style type="text/css">
    	body {
    		background: #d2d6de;
    	}

    	h1 {
    		width: 5rem;
    		margin: auto;
    		margin-top:4rem;
    		margin-bottom: 2rem;
    	}

    	* {
    		outline: none;
    	}

    </style>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-sm-12">
		<h1>LMS</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<p class="text-center">Use the form below to change your password.</p>
			@if ($errors->any())
			<div class = "alert alert-danger alert-dissmissable">
			<button type = "button" class = "close" data-dismiss = "alert">X</button>
			    <ul>
			        @foreach ($errors->all() as $error)
			            <li>{{ $error }}</li>
			        @endforeach
			    </ul>
			</div>
			@endif
			@if(Session::has('success-msg'))
	        <div class = "box-body">           
	            <div class = "alert alert-success alert-dissmissable">
	                <button type = "button" class = "close" data-dismiss = "alert">X</button>
	                <input type = "hidden" class = "global-remove-url" value = "{{URL::route('remove-global', array('success-msg'))}}">
	                {{ Session::get('success-msg') }}
	            </div>            
	        </div>
	        @endif
	        @if(Session::has('error-msg'))
	        <div class = "box-body">	        
	            <div class = "alert alert-danger alert-dissmissable">
	                <button type = "button" class = "close" data-dismiss = "alert">X</button>
	                <input type = "hidden" class = "global-remove-url" value = "{{URL::route('remove-global', array('error-msg'))}}">
	                {{ Session::get('error-msg') }}
	            </div>            
	        </div>
	        @endif
			<form method="post" id="passwordForm" action="{{route('change-password-from-link')}}">
				<input type="password" class="input-lg form-control" name="password" id="password" placeholder="New Password" autocomplete="off">
				<br>
				<div class="row">
					<div class="col-sm-6">
						<span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 Characters Long<br>
						<span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Uppercase Letter
					</div>
					<div class="col-sm-6">
						<span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Lowercase Letter<br>
						<span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Number
					</div>
				</div>
				<br>
				<input type="password" class="input-lg form-control" name="confirm_password" id="confirm_password" placeholder="Repeat Password" autocomplete="off">
				<br>
				<div class="row">
					<div class="col-sm-12">
						<span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Passwords Match
					</div>
				</div>
				<br>
				<input type="hidden" name="email" value="{{$email}}">
				{{csrf_field()}}
				<input type="submit" class="col-xs-12 btn btn-primary btn-load btn-lg" data-loading-text="Changing Password..." value="Change Password" id="change-password-btn">
			</form>
		</div><!--/col-sm-6-->
	</div><!--/row-->
</div>
</body>
<script type="text/javascript">
	let ucase = '';
	let lcase = '';
	let num = '';

	$(document).ready(function () {
		$('#change-password-btn').prop('disabled', true);
		$("input[type=password]").keyup(function(){
    		ucase = new RegExp("[A-Z]+");
			lcase = new RegExp("[a-z]+");
			num = new RegExp("[0-9]+");

		if(checkPasswordValidity(ucase, lcase, num))
		{
			$('#change-password-btn').removeAttr('disabled');
		}
		else
		{
			$('#change-password-btn').prop('disabled', true);
		}
	});

	function checkPasswordValidity(ucase, lcase, num)
	{
		if($("#password").val().length >= 8){
			$("#8char").removeClass("glyphicon-remove");
			$("#8char").addClass("glyphicon-ok");
			$("#8char").css("color","#00A41E");

		}else{
			$("#8char").removeClass("glyphicon-ok");
			$("#8char").addClass("glyphicon-remove");
			$("#8char").css("color","#FF0004");

			return false;
		}
		
		if(ucase.test($("#password").val())){
			$("#ucase").removeClass("glyphicon-remove");
			$("#ucase").addClass("glyphicon-ok");
			$("#ucase").css("color","#00A41E");

		}else{
			$("#ucase").removeClass("glyphicon-ok");
			$("#ucase").addClass("glyphicon-remove");
			$("#ucase").css("color","#FF0004");

			return false;
		}
		
		if(lcase.test($("#password").val())){
			$("#lcase").removeClass("glyphicon-remove");
			$("#lcase").addClass("glyphicon-ok");
			$("#lcase").css("color","#00A41E");
		}else{
			$("#lcase").removeClass("glyphicon-ok");
			$("#lcase").addClass("glyphicon-remove");
			$("#lcase").css("color","#FF0004");

			return false;
		}
		
		if(num.test($("#password").val())){
			$("#num").removeClass("glyphicon-remove");
			$("#num").addClass("glyphicon-ok");
			$("#num").css("color","#00A41E");
		}else{
			$("#num").removeClass("glyphicon-ok");
			$("#num").addClass("glyphicon-remove");
			$("#num").css("color","#FF0004");

			return false;
		}
		
		if($("#password").val() == $("#confirm_password").val()){
			$("#pwmatch").removeClass("glyphicon-remove");
			$("#pwmatch").addClass("glyphicon-ok");
			$("#pwmatch").css("color","#00A41E");
		}else{
			$("#pwmatch").removeClass("glyphicon-ok");
			$("#pwmatch").addClass("glyphicon-remove");
			$("#pwmatch").css("color","#FF0004");

			return false;
		}

		return true;
	}
});
</script>
</html>