@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/form.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Exam Manager</b></h4>		
		<div class="box"> 
			<div class="box-body">	
				@include('exam::tabs')	
				<div class="row">
					<div class="col-sm-4">
						<label>Academic Session: </label>
						<select class="form-control select2" id="session_id" name="session_id">
						    <option value="">Select</option>
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
					<div class="col-sm-4">
						<label>Exam / Assessment: </label>
						<select id="exam_id" class="select2 form-control">
							<option value="">Please select academic session first</option>
						</select>
					</div>
					<div class="col-sm-4">
                        <label>Subject: </label>
                        <select class="form-control select2 @if($errors->first('subject_id')) form-error @endif" id="subject_id" name="subject_id">
                            <option value="">Select</option>
                            @foreach($subjects as $index => $sub)
                            <option value="{{$sub->id}}" 
                                @if($selected_subject_id == $sub->id)
                                selected
                                @endif
                            >{{$sub->subject_name}} -- {{$sub->course_type}} -- {{$sub->course_title}}</option>
                            @endforeach
                        </select>
                    </div>
				</div>
				<input type="hidden" name="selected_exam_id" id="selected_exam_id" value="{{$selected_exam_id}}">
				
				@if(!is_null($student_exam_marks))
					@if(count($student_exam_marks))
					<?php $i=1; ?>
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="marks-table">        
							<thead>
								<tr style="background-color:#333; color:white;">
								<th>SN</th>
								<th>Student Name / ID</th>
								<th>Full Marks</th>
								<th>Obtained Marks</th>	
								<th>Action</th>										
								</tr>
							</thead>
							<tbody>
								@foreach($student_exam_marks as $index => $record)
								<tr>
									<td>{{$i++}}</td>
									<td>{{$record->name .' / '. $record->uniqueId}} 										
									</td>
									<td>{{$exam_full_marks}}</td>
									<td>{{$record->obtained_marks}}</td>
									<td>
										<a class="btn btn-primary btn-flat"  data-toggle="modal" data-target="#editUplodedMarks{{$record->student_id}}" data-title="View Student" data-message="View Student">Edit
	                                    <i class="glyphicon glyphicon-file"></i> 
	                                    </a>            
	                                    @include('exam::modal.edit-uploaded-marks-modal')   
									</td>
								</tr>
								@endforeach				
							</tbody>       								
					    </table>
					</div>
					<input type="hidden" name="exam_full_marks" id="exam_full_marks" value="{{$exam_full_marks}}">					
					@else
					<div class="alert alert-danger alert-dismissable">
		  				<h4><i class="icon fa fa-warning"></i>Enrolled student not available</h4>
		 			</div>	
		 			@endif
	 			@else
	 			<div class="alert alert-warning alert-dismissable">
	  				<h4><i class="icon fa fa-warning"></i>Please select academic session, exam and subject</h4>
	 			</div>	
				@endif
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="role" value="{{$role}}">
<input type="hidden" id="selected_subject_id" value="{{$selected_subject_id}}">
@if($role == 'teacher')
<input type="hidden" value="{{$teacher_id}}" id="teacher_id">
@endif
@endsection
@section('custom-js')
<script type="text/javascript" src="{{asset('public/sms/assets/js/lity.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>

    const config = {
        routes: {                      
            getAssignedTeacherSubjectsRoute : '{{route("ajax-get-teacher-assigned-subjects-from-session")}}'         
        }
    };

    $(document).ready( function () {    	
    	$('.select2').select2();
    	$('#marks-table').DataTable();

 		

 		$('#subject_id').on('change', function(){
    		updateStudentMarks();
    	});

    	$('#exam_id').on('change', function() {
    		updateStudentMarks();    		
    	})

        if($('#role').val() == "teacher")
        {       
            const updateTeacherAssignedSubjectList = (session_id, teacher_id, selected_subject_id) => {                
                $('#subject_id').html('<option>Loading</option>')
                $.ajax({
                    type : 'GET', 
                    url: config.routes.getAssignedTeacherSubjectsRoute, 
                    data: {
                        session_id, teacher_id, 
                        selected_subject_id
                    }, 
                    success: function(data) {                        
                        $('#subject_id').html(data);
                    }, 
                    error: function(jqXHR)
                    {                   
                        toastr.error(jqXHR.statusText + " " + jqXHR.status);
                    }
                });
            }


            if($('#session_id').val())
            {   

                updateTeacherAssignedSubjectList($('#session_id').val(), $('#teacher_id').val(), $('#selected_subject_id').val());
            }

            $('#session_id').on('change', function () { 
                updateExamsList($('#session_id').val(), '');
                updateTeacherAssignedSubjectList($(this).val(), $('#teacher_id').val(), '');
            })      

        }
        else
        {
            $('#session_id').on('change', function(){
                updateExamsList($('#session_id').val(), '');
                updateStudentMarks();
            });
        }

    	const updateStudentMarks = () => 
    	{
    		const session_id = $('#session_id').val(); 
    		const subject_id = $('#subject_id').val(); 
    		const exam_id = $('#exam_id').val();
    		if(session_id && subject_id && exam_id)
    		{
    			let current_url = $('#current_url').val();
    			current_url += `?session_id=${session_id}&exam_id=${exam_id}&subject_id=${subject_id}`;
    			window.location.replace(current_url);
    		}
    	}

    	const updateExamsList = (session_id, selected_exam_id) =>
    	{    		
    		if(session_id)
    		{
    			$.ajax({
    				type: 'GET', 
    				url: '{{route("ajax-get-exam-select-list-from-session")}}', 
    				data : {
    					session_id, 
    					selected_exam_id

    				}, 
    				success:function(data)
    				{    				                    
    					$('#exam_id').html(data);
    				}, 
    				error: function (jqXhr, textstatus) {
						toastr.error(textstatus);
					}
    			})
    		}	
    	}  

    	if($('#session_id').val())
    	{
    		updateExamsList($('#session_id').val(), $('#selected_exam_id').val());
    	}    	  

    	const updateMarks = (student_id, new_marks, session_id, exam_id, subject_id, current_element) => {    		
    		$.ajax({
                type: 'POST', 
                url : '{{route("ajax-update-exam-marks")}}', 
                data: {
                    student_id,                     
                    new_marks, 
                    session_id, 
                    exam_id, 
                    subject_id
                }, 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },                
                success: function(response){                    
                    if(response.status == "success")
                    {
                    	toastr.success(response.msg);
                    	current_element.parent().parent().parent().parent().parent().prev().text(parseFloat(new_marks).toFixed(2));
                        current_element.parent().prev().find('#current-obtained-marks').val(parseFloat(new_marks).toFixed(2));
                    }
                    if(response.status == "error")
                    {
                    	toastr.error(response.msg);
                    }
                }, 
                error: function(xhr) {
                    toastr.error('Request Failed');
                }     

    		});
    	}
    	
        $(document).on('click', '.edit-marks-btn', function(e){
            e.preventDefault(); 
            let student_id = $(this).parent().prev().find('.student-id').val();
            let new_marks = $(this).parent().prev().find('.new-marks').val();
            console.log(new_marks);
            let full_marks = $('#exam_full_marks').val();
            let session_id = $('#session_id').val(); 
    		let subject_id = $('#subject_id').val(); 
    		let exam_id = $('#exam_id').val();
    		let current_element = $(this);
    		
            if(new_marks)
            {
            	if(parseFloat(new_marks) < 0 || parseFloat(new_marks) > parseFloat(full_marks) || !new_marks.match(/^[-]?\d+(\.\d{1,2})?$/))
            	{
            		toastr.error('The new marks should be between 0 and full marks. It should also have 2 decimal points only.');
            	}
            	else
            	{
            		updateMarks(student_id, new_marks, session_id, exam_id, subject_id, current_element);
            	}
            }
            else
            {
            	toastr.error('Please enter the new marks as a number');
            }
            
        });
	});
    

</script>
@endsection