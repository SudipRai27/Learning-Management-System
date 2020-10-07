@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Courses Manager</b></h4>
	@include('course::tabs')
	<!-- <a href="{{route('list-course-type')}}" type="button" class="btn btn-danger">Go Back</a><br><br> -->
		<div class="box"> 
			<div class="box-body">							
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
          		<div class="form-header"><p class="form-header">Create Course Type / Category</p></div>
				<form action="{{route('create-course-work')}}" method="POST" enctype="multipart/form-data" class="crud-form">
					<div class="box-body">
						<div class="form-group">
						<label>Course Work / Type</label>
						<input type="text" name="course_type" id ="course_type" class="form-control @if($errors->first('course_type')) form-error @endif" value="{{ Input::old('course_type')}}" placeholder="Course Type">
						</div>
						<div class="form-group">
						<b>Description:</b> <textarea type="text" name="description" class="form-control" placeholder="Description" rows="6">{{ Input::old('description')}}</textarea>
						<br>
						<input type="submit" value="Create" class="btn btn-primary">
					</div>
				{{ csrf_field() }}
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript">
	$(document).ready(function(){
		$('#course_type').keypress(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});		
	});
</script>
@endsection
