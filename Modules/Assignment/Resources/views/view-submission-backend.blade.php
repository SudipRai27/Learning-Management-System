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
                @include('assignment::tabs')    
                <div class="row">
                    <div class="col-sm-4">
                        <label>Academic Session: </label>
                        <select class="form-control select2" id="session_id" name="session_id">
                            <option value="">Select</option>
                            @foreach($academic_session as $index => $d)
                            <option value="{{$d->id}}"                              
                                @if($d->id == $selected_session_id)
                                selected
                                @endif                              
                                >{{$d->session_name}} {{$d->is_current == "yes" ? '-- Current Session' : ''}}
                            </option>                               
                            @endforeach                            
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
                    <div class="col-sm-4">
                        <label>Assignment: </label>
                        <select id="assignment_id" class="select2 form-control">
                            <option value="">Please select academic session and subject first</option>
                        </select>
                    </div>                  
                </div>
                <input type="hidden" name="selected_assignment_id" id="selected_assignment_id" value="{{$selected_assignment_id}}">
                @if(!is_null($student_submission))
                    @if(count($student_submission['submissions']))
                    
                    <?php $i=1; ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="assignment-submission-table">
                            <thead>
                                <tr style="background-color:#333; color:white;">
                                <th>SN</th>
                                <th>Student</th>                            
                                <th>Status</th>
                                <th>Files</th>
                                <th>Submitted at</th>
                                <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $i = 1;
                            ?>
                            @foreach($student_submission['submissions'] as $index => $d)                        
                                <tr>
                                <td>{{$i++}}</td>
                                <td>{{$d->name }} / {{ $d->uniqueId}}</td>
                                <td>
                                    @if(count($d->submission))
                                        <span class="success-badge">Submitted</span>
                                    @else
                                        <span class="error-badge">Not Submitted</span>
                                    @endif
                                </td>
                                <td>
                                    @if(count($d->submission))                  
                                        <ul>
                                        @foreach($d->submission as $index => $resource)
                                            <li><a href="{{$resource->s3_url}}">{{$resource->filename}}</a></li>
                                        @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($d->updated_at))
                                        {{(new \App\DayAndDateTime)->changeDateFormat($d->updated_at) }}
                                    @endif
                                </td>                                   
                                <td>            
                                    @if(isset($d->submission_id))
                                    <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteSubmission{{$d->submission_id}}" data-title="Delete Submission" data-message="Are you sure you want to delete this user ?">
                                        <i class="glyphicon glyphicon-trash"></i> 
                                    </a>            
                                    @include('assignment::modal.delete-assigment-submission-modal')                 
                                    @endif                       
                                </td>   
                                </tr>
                            @endforeach
                            </tbody>                                    
                        </table>
                    </div>                    
                    </form>
                    @else
                    <div class="alert alert-danger alert-dismissable">
                        <h4><i class="icon fa fa-warning"></i>Enrolled student not available</h4>
                    </div>  
                    @endif
                @else
                <div class="alert alert-warning alert-dismissable">
                    <h4><i class="icon fa fa-warning"></i>Please select academic session, assignment and subject</h4>
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
        $('#assignment-submission-table').DataTable();

        if($('#role').val() == "teacher")
        {       
            const updateTeacherAssignedSubjectList = (session_id, teacher_id) => {                
                $('#subject_id').html('<option>Loading</option>')
                $.ajax({
                    type : 'GET', 
                    url: config.routes.getAssignedTeacherSubjectsRoute, 
                    data: {
                        session_id, teacher_id, 
                        selected_subject_id: $('#selected_subject_id').val()
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
                updateTeacherAssignedSubjectList($('#session_id').val(), $('#teacher_id').val());
            }

            $('#session_id').on('change', function () {     
                updateTeacherAssignedSubjectList($(this).val(), $('#teacher_id').val());
            })      

        }
        else
        {   
            $('#session_id').on('change', function(){
                let session_id = $('#session_id').val(); 
                let subject_id = $('#subject_id').val(); 
                if(session_id && subject_id)
                {
                    updateAssignmentList(session_id, subject_id);                    
                }                
            });             


        }

        const updateSubmissionList = () => 
        {
            const session_id = $('#session_id').val(); 
            const subject_id = $('#subject_id').val(); 
            const assignment_id = $('#assignment_id').val();
            
                let current_url = $('#current_url').val();
                current_url += `?session_id=${session_id}&subject_id=${subject_id}&assignment_id=${assignment_id}`;
                window.location.replace(current_url);
            
        }

        $('#subject_id').on('change', function() {
            updateAssignmentList($('#session_id').val(), $('#subject_id').val());
        });

        $('#assignment_id').on('change', function() {
            updateSubmissionList();
        });

        

        const updateAssignmentList = (session_id, subject_id) =>
        {
            if(session_id && subject_id)
            {
                $('#assignment_id').html('<option>Loading......</option>');
                $.ajax({
                    type: 'GET', 
                    url: '{{route("ajax-get-assignment-select-list-from-session")}}', 
                    data : {
                        session_id, 
                        subject_id, 
                        selected_assignment_id : $('#selected_assignment_id').val()

                    }, 
                    success:function(data)
                    {                       
                        $('#assignment_id').html(data);
                    }, 
                    error: function (jqXhr, textstatus) {
                        toastr.error(textstatus);
                    }
                })
            }   
        }   
        
        if($('#session_id').val() && $('#selected_subject_id').val())
        {            
            updateAssignmentList($('#session_id').val(), $('#selected_subject_id').val());
        }       
  
    });

</script>
@endsection