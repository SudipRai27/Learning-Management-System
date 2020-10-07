@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
	.select2-container .select2-selection--single {
    	height:34px !important;
    	padding: 3px 0px;
	}

	.select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important; 
    	border-radius: 0px !important; 
	}

</style>
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Subject Manager</b></h4>			
	@include('subject::tabs')
	<!-- <a href="{{route('create-academic-session')}}" type="button" class="btn btn-warning">Create Academic Session</a><br><br> -->
		<div class="box"> 
			<div class="box-body">				
				<div class="row select-div">
					<div class="col-sm-4">
						<select class="form-control select2" name="course_type_id" id="course_type_id">
							<option value="">Select Course Type</option>
							@foreach($course_type as $index=>$d)						
							@if(isset($course_type_id) && $course_type_id == $index)
							<option value="{{$index}}" selected>{{$d}}</option>
							@else
							<option value="{{$index}}">{{$d}}</option>
							@endif
							@endforeach
						</select>
					</div>
					<div class="col-sm-4">
						<select class="form-control select2" name="course_id" id="course_id">
							<option value="">Select Course Type First</option>
						</select>
					</div>
					<div class="col-sm-4">
						<input  value="Switch Search Type" type="submit" class="btn btn-primary btn-flat" id="switch_search">
					</div>
				</div>
				<div class="row" id="search-div">
					<div class="col-sm-6">
						<input type="text" name="" placeholder="Enter Subject Name " class="form-control" id="search-bar">
					</div>
					<div class="col-sm-6">
						<input  value="Switch Search Type" type="submit" class="btn btn-primary btn-flat" id="switch_search2">
					</div>
				</div>
				<br>
				<div class="table-responsive" id="search-results">
					@if(count($subjects))
						<?php  $i =1; ?>						
			        <table class="table table-bordered table-hover">        
						<thead>
							<tr style="background-color:#333; color:white;">
							<th>SN</th>
							<th>Subject Name</th>							
							<th>Is Graded</th>
							<th>Credit Points</th>
							
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
						@foreach($subjects as $index => $d)
							<tr>
							<td>{{$i++}}</td>
							<td>{{$d->subject_name}}</td>
							<td>{{$d->is_graded }}</td>
							<td>{{$d->credit_points }}</td>							
							<td>
								<a data-toggle="modal" data-target= "#viewSubject{{$d->id}}" data-title="View Subject" class="btn btn-success btn-flat" > <i class="fa fa-fw fa-file"></i></a>
								@include('subject::modal.subject-view-modal')									 
								<a href = "{{route('edit-subjects', $d->id)}}"><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> <i class="fa fa-fw fa-edit"></i></button></a>
		                        <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#confirmDelete{{$d->id}}" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
      							<i class="glyphicon glyphicon-trash"></i> 
      							</a> 											
								@include('subject::modal.subject-delete-modal')					 
							</td>
							</tr>
						@endforeach
						</tbody>       								
			        </table>
			        {{ $subjects->appends(Input::all())->render() }}
					@else
					<div class="alert alert-warning alert-dismissable">
	  					<h4><i class="icon fa fa-warning"></i>NO SUBJECTS AVAILABLE</h4>
	 				</div>	
					@endif						
				</div>
				<input type="hidden" name="" value="{{isset($course_type_id) ? $course_type_id : ''}}">
				<input type="hidden" name="selected_course_id" id="selected_course_id" value="{{ isset($course_id) ? $course_id : ''}}">				
				<input type="hidden" name="page" value="{{$page}}" id="page">
				<div class="modal">  				
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		$('.select2').select2();

		$('#search-div').hide();
		updateCourseList();

		$('#course_type_id').change(function(){
			
			updateCourseList();

		});

		$('#course_id').change(function(){
			var course_type_id = $('#course_type_id').val();
			var course_id = $('#course_id').val();
			var page = $('#page').val();
			updateSubjectList(page,course_type_id, course_id);
		})

		function updateCourseList()
		{
			var course_type_id = $('#course_type_id').val();
			var selected_course_id = $('#selected_course_id').val();
			
			$('#course_id').html('<option>Loading.....<option>');
			$.ajax({
				url: '{{ route('get-courses-from-course-type')}}', 
				type: 'GET', 
				data: {course_type_id: course_type_id,
					   selected_course_id : selected_course_id
					},
				success:function(data){
					$('#course_id').html(data);
				}

			});
		}

		function updateSubjectList(page,course_type_id, course_id)
		{	
			var current_url = $('#current_url').val(); 
			current_url += '?course_type_id='+ course_type_id + '&course_id='+course_id + '&page=' + page;
			window.location.replace(current_url);
			
		}

		$('#switch_search').click(function(){
			event.preventDefault();
			$('#search-div').show();
			$('.select-div').hide();
		});

		$('#switch_search2').click(function(){
			event.preventDefault();
			$('#search-div').hide();
			$('.select-div').show();
			var course_type_id = $('#course_type_id').val();
			var course_id = $('#course_id').val();
			var page = $('#page').val();
			updateSubjectList(page,course_type_id, course_id);
		});

		$('#search-bar').keyup(function(){
			var search_term = $(this).val();
			var primary_table = 'subjects';
			$('#search-results').html('<p align="center"><img src = "{{ asset('public/images/loader	.gif')}}"></p>');
			$.ajax({
				url: '{{route('get-ajax-search-results')}}', 
				method: 'get',
				data: {
					search_term : search_term, 
					primary_table : primary_table
				}, 

				success:function(data)
				{
					$('#search-results').html(data);
				}

			});
		})

	});
</script>

@endsection

