@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
    .select2-container .select2-selection--single {
        height:34px !important;
    }

    .select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important; 
        border-radius: 0px !important; 
    }

    .subject-msg {
        text-align: center;
        margin:auto;        
        background: #222d32;
        margin: 2rem 0;
        color: #fff;
        padding: 1rem 0;
    }

    .panel-default {
        max-height: 170px !important;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    <h4><b>Enrollment Manager</b></h4>
    @include('enrollment::tabs')    
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
                <div class="form-header"><p class="form-header">Create Enrollment</p></div>            
                <h4><b>Enrollments</b></h4>
                <div class="box-body">
                    <form method="POST" action="{{route('create-enrollment')}}">          
                    <div class="row">
                        <div class="col-sm-6">                             
                            <label>Select Academic Session: </label>
                            <select class="form-control select2" id="session_id" name="session_id">
                                <option value="">Select</option>
                                @foreach($academic_session as $index => $d)
                                <option value="{{$d->id}}"                              
                                    @if($d->id == Input::old('session_id'))
                                    selected
                                    @endif                              
                                    >{{$d->session_name}} {{$d->is_current == "yes" ? '-- Current Session' : ''}}
                                </option>                               
                                @endforeach
                            </select>                                 
                        </div>
                        <div class="col-sm-6">
                            @if($role == 'student')   
                                <input type="hidden" value="{{$secondary_student_id}}" id="secondary_student_id" name="student_id">
                            @else                                         
                                <label>Student:</label>                                
                                <select class="form-control" id="student_id" name="student_id"></select>                            
                            @endif
                        </div>
                    </div>
                    <div class="row">                          
                        <div class="col-sm-6">
                            <label>Current Course Type:</label>
                            <input type="text" class="form-control" name="" readonly id="course_type">
                            <input type="hidden" name="course_type_id" id="course_type_id">
                        </div>
                        <div class="col-sm-6">
                            <label>Current Course:</label>                          
                            <input type="text" class="form-control" name="" readonly id="course_title"> <input type="hidden" name="course_id" id="course_id"> 
                        </div>
                    </div>
                    <br>
                    <input type = "button" class="btn btn-primary btn-flat" value="Enrollment List" id="subject-enrollment-list-button">
                    @include('enrollment::modal.subject-list-modal')                    
                    {{csrf_field()}}
                    </form>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">                                
                            <div class="subject-list">
                            </div>
                        </div>
                    </div>
                </div>                                    
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="{{$role}}" id="role">
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-student-autocomplete-function.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/checkAcademicSessionAccess.js')}}"></script>
<script type="text/javascript">
    const config = {
        routes: {            
            updateStudentAutoCompleteRoute: '{{ route("get-student-autocomplete")}}', 
            checkAcademicSessionSetting: '{{route("check-setting-according-to-session")}}'
        }
    };
    
    $(document).ready(function(){

        $('.enrollment-button').hide();
        $('.select2').select2();

        getStudentAutocomplete(); 

        $('#student_id').change(function(){
            updateSubjectList($('#session_id').val(), $('#student_id').val());
        });

        if($('#role').val() == "student")
        {
            $('#session_id').change(function() {               
                let response = checkAccessForSession($(this).val(), 'can_enroll');
                if(response.status == 200)
                {
                    if(response.responseText == "yes")
                    {
                        updateSubjectList($(this).val(), $('#secondary_student_id').val());
                    }
                    else if(response.response = "no")
                    {
                        $('.subject-list').html('<div class="alert alert-danger alert-dismissable"><h4><i class="icon fa fa-warning"></i>Enrollments are closed for this session</h4></div>');
                    }
                    else
                    {
                        $('.subject-list').html('<div class="alert alert-danger alert-dismissable"><h4><i class="icon fa fa-warning"></i>Settings unavailable</h4></div>');   
                    }
                } 
                
            });            
        }
        else
        {
            $('#session_id').change(function() {
                updateSubjectList($(this).val(), $('#student_id').val());
            });    
        }
    
        function updateSubjectList(session_id, student_id)
        {            
            if(student_id && session_id)
            {                
                updateEnrolledSubjectList(student_id, session_id);
                updateCourseAndCourseType(student_id);
                $('#subject-enrollment-table').children().remove();
            }
            else
            {
                $('.subject-list').html('<div class="alert alert-warning alert-dismissable"><h4><i class="icon fa fa-warning"></i>Please select available options</h4></div>');
            }
        }

        if($('#role').val() == "student")
        {            
            updateCourseAndCourseType($('#secondary_student_id').val(), $('#session_id').val());
        }

        function updateEnrolledSubjectList(student_id, session_id)
        {
            $('.subject-list').html('<p align="center"><img src = "{{ asset('public/images/loader.gif')}}"></p>');
            $.ajax({
                url: '{{route('get-subjects-from-student-id-current-course-and-course-type')}}', 
                data: {
                    student_id: student_id, 
                    session_id: session_id
                },
                type: 'GET', 
                success: function(data) {                
                   $('.subject-list').html(data);
                }, 
                error:function(jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                }  
            });
        }

        function updateCourseAndCourseType(student_id)
        {
            $.ajax({
                type: 'GET', 
                data: {
                    student_id: student_id
                }, 
                url: '{{route('get-current-course-and-type-from-student-id')}}', 
                success: function(data) {
                    $('#course_title').val(data.course_title);
                    $('#course_type').val(data.course_type);
                    $('#course_type_id').val(data.course_type_id);
                    $('#course_id').val(data.course_id);
                }, 
                error:function(jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                }  
            });
        }

        $('#subject-enrollment-list-button').click(function(){
            $('#subject-enrollment-modal').modal();
        });

    });

</script>
@endsection