@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
	.settings-wrapper {
		width: 100%;
		margin: 2rem auto;
		padding: 1rem;
	}

	.setting-heading {
		width: 50%;
		margin:2rem auto;
		padding: 1rem;
		background: #e8c6b3;
		text-align: center;		
		font-size: 1.7rem;

	}

	.settings {
		display: flex;
		justify-content: space-between;
		padding:2rem;
		margin: 1rem 0;
		background-color: #cad7e5;
	}

	.button {
		width: 50%;
		margin: auto;
		text-align: center;
	}

</style>
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Academic Session Manager</b></h4>
	@include('academicsession::tabs')	
		<div class="box"> 
			<div class="box-body">	
				<div class="row">
					<div class="col-sm-6">
					<label>Academic Session: </label>
					<select class="form-control select2" id="session_id" name="session_id">
					    <option value="0">Select</option>
					    @foreach($academic_session as $index => $d)
					    <option value="{{$d->id}}"
					        @if(isset($selected_session_id))
					        @if($d->id == $selected_session_id)
					        selected
					        @endif
					        @endif
					        >{{$d->session_name}} {{ $d->is_current == "yes" ? '-- Current Session --' : '' }}
					    </option>                               
					    @endforeach
					</select>    
					</div>
				</div>
				@if(!is_null($session_settings))
					@if($session_settings)
					<div class="setting-heading">Manage settings</div>
					<form action="{{route('update-settings')}}" method="POST">
						<div class="settings-wrapper">
							<div class="settings">
								<label>Students Can Enroll</label>
								<div>
									<input type="radio" name="can_enroll" value="yes" @if($session_settings->can_enroll == "yes") checked @endif> Yes
									<input type="radio" name="can_enroll" value="no" @if($session_settings->can_enroll == "no") checked @endif> No
								</div>
							</div>
							<div class="settings">
								<label>Students Can Update Timetable</label>
								<div>
									<input type="radio" name="can_update_timetable" value="yes" @if($session_settings->can_update_timetable == "yes") checked @endif> Yes
									<input type="radio" name="can_update_timetable" value="no" @if($session_settings->can_update_timetable == "no") checked @endif> No
								</div>
							</div>
							<div class="settings">
								<label>Teachers Can Update Attendance</label>
								<div>
									<input type="radio" name="can_update_attendance" value="yes" @if($session_settings->can_update_attendance == "yes") checked @endif> Yes
									<input type="radio" name="can_update_attendance" value="no" @if($session_settings->can_update_attendance == "no") checked @endif> No
								</div>
							</div>
						</div>
						<input type="hidden" name="session_id" value="{{$selected_session_id}}">
						<div class="button">
							<input type="submit" class="btn btn-success btn-flat" value="Update Settings">
						</div>			
						{{csrf_field()}}			
					</form>
					@else
					<div class="alert alert-danger alert-dismissable">
						<h4><i class="icon fa fa-warning"></i>Settings unavilable</h4>
					</div>	
					@endif
				@else
				<div class="alert alert-warning alert-dismissable">
	  				<h4><i class="icon fa fa-warning"></i>Please select session</h4>
	 			</div>	
				@endif		
			</div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		let current_url = $('#current_url').val();
        $('.select2').select2();

        const getSettings = (session_id) => {
        	current_url += `?session_id=${session_id}`;
        	location.replace(current_url);
        }

        $('#session_id').on('change', () => {
        	const session_id = $('#session_id').val();        	
        	getSettings(session_id);
        })
    });
</script>
@endsection