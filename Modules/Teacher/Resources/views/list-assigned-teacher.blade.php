@extends('backend.main')
@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<style type="text/css">
    .select2-container {
        width: 100% !important;
        padding: 0;
    }

    .teacher-list {
        margin-top: 20px;
        margin-bottom: 10px;
        background-color: #367fa9;
        height: 25px;
    }

    .teacher-list p {
        text-align: center;
        color:white;
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
    <!-- <a href="{{route('list-academic-session')}}" type="button" class="btn btn-danger">Go Back</a><br><br> -->
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
                <div class="row">                
                    <div class="col-sm-3">
                        <br>
                        <button class="btn btn-primary btn-flat" id="switch-search">Switch Lookup</button>
                    </div>           
                </div>    
                <br>                
                <div class="row">
                    <div class="col-sm-6 select-div">
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
                                >{{$d->session_name}} {{$d->is_current == "yes" ? "--Current Session--" : " " }}
                            </option>                               
                            @endforeach
                        </select>    
                    </div>
                    <div class="col-sm-6 select-div">
                        <label>Subject: </label><br>
                        <select class="form-control select2" id="subject_id" name="subject_id">
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
                    <div class="col-sm-6 select-teacher-div">
                        <label>Academic Session:</label>
                        <select class="form-control select2" id="secondary_session_id" name="secondary_session_id">
                            <option value="0">Select</option>                            
                            @foreach($academic_session as $index => $d)
                            <option value="{{$d->id}}">{{$d->session_name}}</option>
                            @endforeach
                        </select>
                    </div>                               
                    <div class="col-sm-6 select-teacher-div">
                        <label>Teacher:</label><br>
                        <select class="form-control" id="teacher_id" name="teacher_id"></select>
                    </div>                                                                  
                </div>
                <br>
                <div id="ajax-assigned-teacher-list">
                </div>
                @if(!is_null($assigned_teacher_list))
                <div class="normal-teacher-table-list">
                    <div class="teacher-list">
                        <p>Lecture Session</p>
                    </div>
                    @if(count($assigned_teacher_list['lecturer']))
                    <table class="table table-bordered table-hover" id="lecturer-table">        
                        <thead>
                            <tr style="background-color:#333; color:white;">
                                <th>SN</th>
                                <th>Teacher ID</th>                         
                                <th>Teacher Name</th>
                                <th>Email</th>                            
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                            ?>
                            @foreach($assigned_teacher_list['lecturer'] as $index => $data)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$data->teacher_id}}</td>
                                <td>{{$data->name}}</td>
                                <td>{{$data->email}}</td>
                                <td>
                                    <button class="btn btn-primary btn-flat" type="button" data-toggle="modal" data-target="#editAssignedLecturer{{$data->id}}" data-title="Edit Lecturer" data-message="Edit Lecturer"> Edit <i class="fa fa-fw fa-edit"></i></button>                    
                                    @include('teacher::modal.edit-assigned-lecturer-modal')                    
                                    <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteAssignedTeacher{{$data->id}}" data-title="Delete Lecturer" data-message="Are you sure you want to delete this lecturer ?">
                                    <i class="glyphicon glyphicon-trash"></i> 
                                    </a>            
                                    @include('teacher::modal.delete-assigned-teacher-modal')                 
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-warning alert-dismissable">
                        <h4><i class="icon fa fa-warning"></i>Not assigned for this session</h4>
                    </div>  
                    @endif
                    <div class="teacher-list">
                        <p>Lab Session</p>
                    </div>       
                    @if(count($assigned_teacher_list['tutor']))  
                    <table class="table table-bordered table-hover" id="tutor-table">        
                        <thead>
                            <tr style="background-color:#333; color:white;">
                                <th>SN</th>
                                <th>Teacher ID</th>                         
                                <th>Teacher Name</th>
                                <th>Email</th>                            
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                            ?>
                            @foreach($assigned_teacher_list['tutor'] as $index => $data)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$data->teacher_id}}</td>
                                <td>{{$data->name}}</td>
                                <td>{{$data->email}}</td>
                                <td>
                                    <button class="btn btn-primary btn-flat" type="button" data-toggle="modal" data-target="#editAssignedTutor{{$data->id}}" data-title="Edit Tutor" data-message="Edit Tutor"> Edit <i class="fa fa-fw fa-edit"></i></button>                    
                                    @include('teacher::modal.edit-assigned-tutor-modal')           

                                    <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteAssignedTeacher{{$data->id}}" data-title="Delete Lecturer" data-message="Are you sure you want to delete this lecturer ?">
                                    <i class="glyphicon glyphicon-trash"></i> 
                                    </a>            
                                    @include('teacher::modal.delete-assigned-teacher-modal')        
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-warning alert-dismissable">
                        <h4><i class="icon fa fa-warning"></i>Not assigned for this session</h4>
                    </div>  
                    @endif
                </div>
                @endif                                                                                  
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom-js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/
update-teacher-by-type-function.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/check-assigned-teacher-function.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/check-teacher-classes-function.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">

    const config = {
        routes: {                      
            getTeacherByTypeAutoCompleteRoute: '{{route("get-teacher-by-type-autocomplete")}}',
            checkAssignedTeacherRoute: '{{route("check-assigned-teacher")}}',            
            checkTeacherClasses: '{{route("ajax-check-teacher-classes")}}',            
        }
    };

    $(document).ready(function() {

        $('.select2').select2();
        /*DataTables */
        $('#lecturer-table').DataTable();
        $('#tutor-table').DataTable();
        /*DataTables*/

        /* Toggling Divs Start */
        $('.select-teacher-div').hide();
        $('#ajax-assigned-teacher-list').hide();
        
        $('#switch-search').on('click', function(e) {
            e.preventDefault();
            toggleDivs();           

        });

        function toggleDivs()
        {
            if($('.select-teacher-div').is(":hidden"))
            {
                $('.select-teacher-div').show();
                $('.select-div').hide();
                $('#ajax-assigned-teacher-list').show();
                $('.normal-teacher-table-list').hide();           
            }
            else
            {
                $('.select-teacher-div').hide();
                $('.select-div').show();
                $('.normal-teacher-table-list').show();
                $('#ajax-assigned-teacher-list').hide();
                
            }
        }
        /* Toogling Divs End */

        
        // Session and  subject

        $('#session_id').on('change', function() {
            if($('#subject_id').val() && $('#session_id').val())
            {
                updateTeacherList($('#session_id').val(),$('#subject_id').val());
            }
                 
        });

        $('#subject_id').on('change', function() {
            updateTeacherList($('#session_id').val(),$('#subject_id').val());
        });

        function updateTeacherList(session_id, subject_id)
        {
            let current_url = $('#current_url').val(); 
            current_url += '?session_id=' + session_id + '&subject_id=' + subject_id;            
            window.location.replace(current_url);                      
        }     

        /*Session, Course Type, Course Type, Course, Subject List End*/ 


        /*Session, Teacher , Subject List Start */
        let selectorId = 'teacher_id';
        updateTeacherByType(selectorId, 'all');

        $('#teacher_id').on('change', function() {
            const secondary_session_id = $('#secondary_session_id').val(); 
            const teacher_id = $('#teacher_id').val();            
            if(secondary_session_id != 0)
            {
                updateAssignedTeacherFromSessionAndTeacherID(secondary_session_id, teacher_id); 
            }
        });

        $('#secondary_session_id').on('change', function() {
            if($('#secondary_session_id').val() && $('#teacher_id').val() != null)
            {
                updateAssignedTeacherFromSessionAndTeacherID($('#secondary_session_id').val(), $('#teacher_id').val());             
            }
        });

        function updateAssignedTeacherFromSessionAndTeacherID(session_id, teacher_id) {
            $.ajax({
                type : 'GET', 
                url: '{{route("get-assigned-teacher-list-from-session-id-teacher-id")}}',
                data: {
                    session_id, teacher_id
                },
                success:function (data) {
                    $('.normal-teacher-table-list').hide();
                    $('#ajax-assigned-teacher-list').show();
                    $('#ajax-assigned-teacher-list').html(data);
                    $('#assigned-teacher-from-session-and-teacher-id').DataTable();
                    
                }, 
                error: function( jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                }     
            });
        }       
        /*Session, Teacher , Subject List End */
    });

    /*remove ajax assigned teacher list */

    

</script>

@endsection
