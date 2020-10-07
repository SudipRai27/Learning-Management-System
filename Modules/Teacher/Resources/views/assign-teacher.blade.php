@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css">
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
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    <h4><b>Teacher Manager</b></h4>
    @include('teacher::tabs')
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
                <div class="form-header"><p class="form-header">Assign Teacher </p></div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
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
                                >{{$d->session_name}} {{ $d->is_current == "yes" ? '--Current Session --' : '' }}
                            </option>                               
                            @endforeach
                        </select>    
                    </div>
                </div>
                <div class="row">
                    @include('backend.partials.course-type-course-partial')
                </div>
                </div>   
                    @if($subjects_list)
                    <?php  $i =1; ?>                        
                    <br>
                    <table class="table table-bordered table-hover" id="myTable">        
                        <thead>
                            <tr style="background-color:#333; color:white;">
                            <th>SN</th>
                            <th>Subject Name</th>                         
                            <th>Credit Points</th>
                            <th>Course Type</th>
                            <th>Course</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($subjects_list as  $index => $subject)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$subject->subject_name}}</td>
                                <td>{{$subject->credit_points}}</td>
                                <td>{{$subject->course_type}}</td>
                                <td>{{$subject->course_title}}</td>
                                <td>
                                    <a class="btn btn-warning btn-flat"  data-toggle="modal" data-target="#assignTeacher{{$subject->id}}" data-title="View Student" data-message="View Student">Assign Teacher
                                    <i class="glyphicon glyphicon-file"></i> 
                                    </a>            
                                    @include('teacher::modal.assign-teacher-modal')   
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                    <br>
                    <div class="alert alert-warning alert-dismissable">
                        <h4><i class="icon fa fa-warning"></i>Please select session, course type and program first</h4>
                    </div>  
                    @endif                                                      
                <input type="hidden" id="selected_course_id" value="{{$selected_course_id}}">            
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-course-function.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/check-assigned-teacher-function.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-teacher-by-type-function.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">

    const config = {
        routes: {
            updateCourseRoute: '{{route("get-courses-from-course-type")}}',             
            checkAssignedTeacherRoute: '{{route("check-assigned-teacher")}}', 
            // getTeacherByTypeAutoCompleteRoute: '{{route("get-teacher-by-type-autocomplete")}}'
        }
    };    
    
    $(document).ready(function() {

        $('.select2').select2();

        if($('#course_type_id').val() != 0)
        {
            updateCourse($('#course_type_id').val());
        }

        $('#myTable').DataTable();

        $('#session_id').on('change', function() {

            if($('#session_id').val() && $('#course_type_id').val() && $('#course_id').val())
            {
                updateSubjectList();
            }                
            else
            {
                toastr.info('Please select all option fields');
            }
        });

        $('#course_type_id').on('change', function() {
            updateCourse($('#course_type_id').val());
        });

        $('#course_id').on('change', function() {
            if($('#session_id').val() && $('#course_type_id').val() && $('#course_id').val())
            {
                updateSubjectList();
            }
            else
            {
                toastr.info('Please select all option fields');
            }
        });

        function updateSubjectList()
        {
            let current_url = $('#current_url').val(); 
            current_url += '?session_id=' + $('#session_id').val() + '&course_type_id=' + $('#course_type_id').val() + '&course_id=' + $('#course_id').val();
            window.location.replace(current_url);
            
        }
    });
    


</script>

@endsection
