@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Update Course</b></h4>
	<a href="{{route('list-course')}}" type="button" class="btn btn-danger">Go Back</a><br><br>
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
          		<div class="form-header"><p class="form-header">Edit Course</p></div>
				<form action="{{route('edit-course', $course->id)}}" method="POST" enctype="multipart/form-data" class="crud-form">
					<div class="box-body">
						<div class="form-group">
							<label>Course Work </label>
							<input type="text" name="course_title" id="course_title" class="form-control @if($errors->first('course_title')) form-error @endif" value="{{ $course->course_title }}" placeholder="Course Title">
						</div>
						<div class="form-group">
							<label>Course Type</label>
							<select class="form-control @if($errors->first('course_type_id')) form-error @endif" name="course_type_id" id="course_type_id">
								@foreach($course_type as $index => $d)
								<option value="{{$d->id}}" @if($d->id == $course->course_type_id) selected @endif>{{$d->course_type}}</option>	
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<b>Description:</b> <textarea type="text" name="description" class="form-control" placeholder="Description" rows="6">{{ $course->description}}</textarea>
						<br>
						<input type="submit" value="Update" class="btn btn-primary">
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


		$('#course_title').click(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});
		$('#course_type_id').change(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});		

		/*function removeError(course_title)
		{
			console.log($(this));
		}*/
	});
</script>
@endsection
