@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<style type="text/css">
    .select2-container {
        width: 100% !important;
        padding: 0;
    }

    .select2-container .select2-selection--single {
        height:34px !important;
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
    <h4><b>Enrollment Manager</b></h4>         
        @include('enrollment::tabs')
        <div class="box"> 
            <div class="box-body">              
                <div class="row">      
                    @if($role == "student")              
                    <div class="col-sm-8">
                    <label>Academic Session: </label>
                        <select class="form-control select2" id="session_id_student" name="session_id_student">
                            <option value="0">Select</option>
                            @foreach($academic_session as $index => $d)
                            <option value="{{$d->id}}"
                                @if(isset($selected_session_id))
                                @if($d->id == $selected_session_id)
                                selected
                                @endif
                                @endif
                                >{{$d->session_name}} {{$d->is_current == "yes" ? "-- Current Session --" : ''}}
                            </option>                               
                            @endforeach
                        </select>                  
                    </div>
                    @else
                    @include('backend.partials.session-course-type-course-partial')
                    <div class="col-sm-6 select-student-div">
                        <label>Student:</label><br>
                        <select class="form-control select2" id="student_id" name="student_id"></select>
                    </div>                            
                    <div class="col-sm-3 select-student-div">
                        <label>Academic Session:</label>
                        <select class="form-control select2" id="secondary_session_id" name="secondary_session_id">
                            <option value="0">Select</option>                            
                            @foreach($academic_session as $index => $d)
                            <option value="{{$d->id}}">{{$d->session_name}}</option>
                            @endforeach
                        </select>
                    </div> 
                    <div class="col-sm-3">
                        <br>
                        <button class="btn btn-primary btn-flat" id="switch-search">Switch Lookup</button>
                    </div>       
                    @endif                                           
                </div>
                <br>
                <div id="ajax-table-div">
                </div>
                @if(isset($enrollment_subject_records))
                <br>
                <div id="normal-table-div">
                    @if(count($enrollment_subject_records))
                        <?php  $i =1; ?>                        
                    <table class="table table-bordered table-hover" id="normal-table">        
                        <thead>
                            <tr style="background-color:#333; color:white;">
                            <th>SN</th>
                            <th>Student ID</th>                         
                            <th>Student Name</th>
                            <th>Enrolled Subjects</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($enrollment_subject_records as $index => $record)                     
                            <tr>
                            <td>{{$i++}}</td>
                            <td>{{$record['uniqueID']}}</td>
                            <td>{{$record['student_name']}}</td>
                            <td>
                            @foreach(json_decode($record['enrolled_subjects']) as $index => $subject)
                            <li>{{$subject->subject_name}} </li>                         
                            @endforeach
                            </td>                       
                            <td> 
                                <button class="btn btn-primary btn-flat"  data-toggle="modal" data-target="#viewEnrollment{{$record['enrollment_id']}}" data-title="View Enrollment" data-message="View records">
                                <i class="glyphicon glyphicon-file"></i> 
                                </button>                                                      
                                @include('enrollment::modal.view-enrollment-modal') 
                                
                                <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#confirmDelete{{$record['enrollment_id']}}" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
                                <i class="glyphicon glyphicon-trash"></i> 
                                </a>  
                                @include('enrollment::modal.enrollment-delete-modal')                                  
                            </td>                                                                        
                            </tr>
                        @endforeach
                        </tbody>                                    
                    </table>                    
                    @else
                    <div class="alert alert-warning alert-dismissable">
                        <h4><i class="icon fa fa-warning"></i>NO ENROLLMENT RECORDS AVAILABLE</h4>
                    </div>  
                    @endif                      
                </div>                     
                @endif
                <input type="hidden" value="{{isset($selected_session_id) ? $selected_session_id : 0}}" id="selected_session_id">
                <input type="hidden" value="{{isset($selected_course_type_id) ? $selected_course_type_id 
                : 0}}" id="selected_course_type_id">
                <input type="hidden" value="{{isset($selected_course_id) ? $selected_course_id : 0}}" id="selected_course_id">
                <input type="hidden" value="{{$secondary_student_id}}" id="secondary_student_id">
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-course-function.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-course-type-function.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-student-autocomplete-function.js')}}"></script>
<script type="text/javascript">
    const config = {
        routes: {
            updateCourseRoute: '{{route("get-courses-from-course-type")}}', 
            updateCourseTypeRoute: '{{route("get-all-course-type-select-box")}}', 
            updateStudentAutoCompleteRoute: '{{ route("get-student-autocomplete")}}'
        }
    };

    $(document).ready(function() {

        $('.select2').select2();

        $('#normal-table').DataTable();

        /////////////////////////////////////////////////////////////////////////////////////
        /*Toogle Divs */
        $('.select-student-div').hide();
        
        $('#switch-search').on('click', function(e) {
            e.preventDefault();
            toggleDivs();           

        })      

        function toggleDivs()
        {
            if($('.select-student-div').is(":hidden"))
            {
                $('.select-student-div').show();
                $('.select-div').hide();
                $('#ajax-table-div').show();
                $('#normal-table-div').hide();
            }
            else
            {
                $('.select-student-div').hide();
                $('.select-div').show();
                $('#ajax-table-div').hide();
                $('#normal-table-div').show();
            }
        }
        /*Toogle Divs */
        /////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////////////////////////////////////////////////////////////
        /*Session, Course Type, Course */
        if($('#selected_session_id').val() != 0)
        {
            updateCourseType();
        }

        if($('#selected_session_id').val() != 0 || $('#selected_course_type_id').val() != 0)
        {
            updateCourse($('#selected_course_type_id').val());
        }

        $('#session_id').on('change', function() {
            updateCourseType();        
            updateEnrollmentRecords();     
        });

        $('#course_type_id').on('change', function() {
            updateCourse($('#course_type_id').val());
            updateEnrollmentRecords();
        });

        $('#course_id').on('change', function() {
            updateEnrollmentRecords(); 
        });

        function updateEnrollmentRecords()
        {   
            let current_url = $('#current_url').val();        
            current_url += '?session_id=' + $('#session_id').val() +'&course_type_id=' + $('#course_type_id').val()+ '&course_id=' + $('#course_id').val();            
            window.location.replace(current_url);            

        }
        /*Session, Course Type, Course */
        /////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////////////////////////////////////////////////////////////
        /*Sesion, Student ID Functions */

        getStudentAutocomplete(); 

        $('#student_id').on('change', function() {            
            updateStudentEnrollmentRecords(); 
        });

        $('#secondary_session_id').on('change', function() {            
            updateStudentEnrollmentRecords();
        });


        function updateStudentEnrollmentRecords()
        {   
            
            $('#ajax-table-div').html('<p align="center"><img src = "{{ asset('public/images/loader.gif')}}"></p>');
            $.ajax({
                type: 'GET', 
                url: '{{route("get-enrollment-records-from-student-id-session-id")}}', 
                data: {
                    student_id: $('#student_id').val(), 
                    session_id: $('#secondary_session_id').val()

                }, 
                success:function(data) {
                    $('#normal-table-div').hide();
                    $('#ajax-table-div').html(data);
                    $('#ajax-table-div').show();
                    $('#ajax-student-table').DataTable();
                    
                }, 
                error:function(jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                }  
            });                     
        }
    
        /*Sesion, Student ID Functions */
        /////////////////////////////////////////////////////////////////////////////////////
        //For user role of student only///
        $('#session_id_student').on('change', function() {
            if($('#session_id_student').val() == 0)
            {
                $('#ajax-table-div').html('<div class="alert alert-warning alert-dismissable"><h4><i class="icon fa fa-warning"></i>Please select session</h4></div>');
            }
            else
            {
                $('#ajax-table-div').html('<p align="center"><img src = "{{ asset('public/images/loader.gif')}}"></p>');
                $.ajax({
                type: 'GET', 
                url: '{{route("get-enrollment-records-from-student-id-session-id")}}', 
                data: {
                    student_id: $('#secondary_student_id').val(), 
                    session_id: $('#session_id_student').val()

                }, 
                success:function(data) {                    
                    $('#ajax-table-div').html(data);
                    $('#ajax-table-div').show();
                    $('#ajax-student-table').DataTable();
                    
                }, 
                error:function(jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                }  
            });  

            }
        });
        //For user role of student only///
        
    });
</script>
@endsection


