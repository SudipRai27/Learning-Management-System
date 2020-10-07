@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Subject Manager</b></h4>
	@include('subject::tabs')
	<!-- <a href="{{route('list-course')}}" type="button" class="btn btn-danger">Go Back</a><br><br> -->
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
          		<div class="form-header"><p class="form-header">Create Subject</p></div>
				<form action="{{route('create-subject')}}" method="POST" enctype="multipart/form-data" class="crud-form">
					<div class="box-body">
						<div class="form-group">
							<label>Subject Name </label>
							<input type="text" id="subject_name" name="subject_name" class="form-control @if($errors->first('subject_name')) form-error @endif" value="{{ Input::old('subject_name')}}" placeholder="Subject Name">
						</div>
						<div class="form-group">
							<b>Description:</b> <textarea type="text" name="description" class="form-control" placeholder="Description" rows="6">{{ Input::old('description')}}</textarea>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Is Graded</label>
									<select class="form-control @if($errors->first('is_graded')) form-error @endif" name="is_graded" id="is_graded">
										<option value="">Select</option>
										<option value="yes" @if(Input::old('is_graded') == 'yes') selected @endif>Yes</option>
										<option value="no" @if(Input::old('is_graded') == 'no') selected @endif>No</option>
									</select>
								</div> 
								<div class="col-sm-6">
									<label>Credit Points</label>
									<input type="text" name="credit_points" placeholder="Credit Points" class="form-control @if($errors->first('credit_points')) form-error @endif" id="credit_points" value="{{Input::old('credit_points')}}">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Course Type</label>
									<select class="form-control @if($errors->first('course_type_id')) form-error @endif" name="course_type_id" id="course_type_id" >
										<option value="">Select</option>
										@foreach($course_type as $index => $d)
										<option value="{{$d->id}}" @if(Input::old('course_type_id') == $d->id) selected @endif>{{$d->course_type}}</option>	
										@endforeach
								</select>
								</div>
								<div class="col-sm-6">
									<label>Course</label>
									<select class="form-control @if($errors->first('course_id')) form-error @endif" name="course_id" id="course_id">
										<option value="">Select Course Type First</option>																	
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Full Marks</label>
									<input type="text" name="full_marks" placeholder="Full Marks" class="form-control @if($errors->first('full_marks')) form-error @endif" id="full_marks" value="{{Input::old('full_marks')}}">
								</div>							
								<div class="col-sm-6">
									<label>Pass Marks</label>
									<input type="text" name="pass_marks" placeholder="Pass Marks" class="form-control @if($errors->first('pass_marks')) form-error @endif" id="pass_marks" value="{{Input::old('pass_marks')}}">
								</div>
							</div>
						</div>
						<br>
						<input type="submit" value="Create" class="btn btn-primary">
					</div>
				{{ csrf_field() }}
				</form>
				<?php
					$old_course_id = Input::old('course_id');	
					
				?>
				<input type="hidden" name="" value="{{Input::old('course_id')}}" id="selected_course_id">
			</div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript">
	$(document).ready(function(){
		
		updateCourseList();

		$('#subject_name').keypress(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});
		$('#is_graded').change(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});		
		$('#credit_points').keypress(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});
		$('#course_id').change(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});
		$('#course_type_id').change(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});
		$('#full_marks').keypress(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});
		$('#pass_marks').keypress(function(){
			$(this).removeClass('form-error')
			$(this).addClass('form-success');
		});


		$('#course_type_id').change(function(){
			updateCourseList();
		});

		function updateCourseList()
		{
			var course_type_id = $('#course_type_id').val();
			var selected_course_id = $('#selected_course_id').val();
			$('#course_id').html('<option>Loading.....<option>');
			$.ajax({
				url: '{{route('get-courses-from-course-type')}}', 
				type: 'GET', 
				data: {course_type_id :course_type_id, 
					   selected_course_id : selected_course_id
						}, 
				
				success:function(data){
					$('#course_id').html(data);
				}
			});

			$(this).removeClass('form-error');
			$(this).addClass('form-success');
			
		}
	});
	
</script>
@endsection
