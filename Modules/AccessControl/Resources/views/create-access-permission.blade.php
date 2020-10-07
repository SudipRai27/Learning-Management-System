 @extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<style type="text/css">
.heading {
	background: #098668;
	color: white;
	width: 90%;
	margin: 1rem auto;
	padding: 1.5rem;
	font-size: 1.9rem;
	text-align: center;
	
}

.sub-heading {
	background: #a91717;
	color: white;
	padding: 0.8rem 0;
	font-size: 1.4rem;
	text-align: center;	
}

.permissions {
	font-size: 1.5rem;
	text-align: center;
}

</style>
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>&nbsp;&nbsp;&nbsp;Create Permissions</b></h4>	
		<a href="{{route('list-modules')}}" type="button" class="btn btn-danger btn-flat">Go Back </a><br><br>
		<div class="box"> 
			<div class="box-body">
				<h3 class="heading">Set permission / access control for {{$module_name}} Module</h3>
				<form action = "{{route('create-permissions-post', $module_name)}}" method = "post">
				<div class = "row">
				<div class = "col-md-6 sub-heading"><b>Permission Type</b></div>
				<div class = "col-md-6 sub-heading"><b>Permission Groups</b></div>
				</div>
				<br>
				@foreach($access as $permission_type => $a)
				<div class = "row">
					<div class = "col-md-6 permissions">
						{{ $permission_type }}
						<input type="hidden" name="permission_type[]" value="{{$permission_type}}">
					</div>
					<div class = "col-md-6">
						<div class = "row permissions">
							@foreach($roles as $role_id => $group_name)
								<span><input type = "checkbox" name ="{{$permission_type}}_[]"  value = "{{ $role_id}}"
								@if(in_array($role_id, $a)) checked @endif>{{$group_name}}</span>
							@endforeach				
						</div>
					</div>
				</div>
				<br>
				@endforeach
				<br>
				<input type = "submit" class = "btn btn-primary btn-flat" value = "Set Permission">

				{{ csrf_field() }}
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

