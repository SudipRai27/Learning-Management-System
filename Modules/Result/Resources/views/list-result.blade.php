@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
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

    .table-header {
        width: 25rem;
        margin: 1rem auto;
        background: #583776;
        color: #fff;
        text-align:center;
        padding: 1rem;
        font-size: 1.6rem;
    }

    .table-responsive > p {
        display: inline-block;
        width: 30rem;
        padding: 1rem;
        background: #c0992c;
        color: white;
        font-size: 1.3rem;
    }   

</style>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    <h4><b>Result Manager</b></h4>
    @include('result::tabs')
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
                    <div class="col-sm-4 select-div">
                        <label>Academic Session: </label>
                        <select class="form-control select2" id="session_id" name="session_id">
                            <option value="">Select</option>
                            @foreach($academic_session as $index => $d)
                            <option value="{{$d['id']}}"
                                @if(isset($selected_session_id))
                                @if($d['id'] == $selected_session_id)
                                selected
                                @endif
                                @endif
                                >{{$d['session_name']}} {{ $d['is_current'] == "yes" ? '--Current Session --' : '' }}
                            </option>                               
                            @endforeach
                        </select>    
                    </div>
                    <div class="col-sm-4 select-div">
                        <label>Course Type / Program Type: </label>
                        <select class="form-control select2" id="course_type_id" name="course_type_id">
                            <option value="">Select</option>
                            @foreach($course_type as $index => $d)
                            <option value="{{$d->id}}"
                            @if($d->id == $selected_course_type_id)
                            selected
                            @endif

                                >{{$d->course_type}}</option>
                            @endforeach
                        </select>    
                    </div>
                    <div class="col-sm-4 select-div">
                        <label>Program: </label>
                        <select class="form-control select2" id="course_id" name="course_id">
                            <option value="0">Please select course type first</option>
                        </select>                    
                    </div>        
                    <div class="col-sm-6 select-student-div">
                        <label>Academic Session:</label>
                        <select class="form-control select2" id="secondary_session_id" name="secondary_session_id">
                            <option value="">Select</option>
                            @foreach($academic_session as $index => $d)
                            <option value="{{$d['id']}}"
                                @if(isset($selected_session_id))
                                @if($d['id'] == $selected_session_id)
                                selected
                                @endif
                                @endif
                                >{{$d['session_name']}} {{ $d['is_current'] == "yes" ? '--Current Session --' : '' }}
                            </option>                               
                            @endforeach
                        </select>
                    </div>                               
                    <div class="col-sm-6 select-student-div">
                        <label>Enrolled Student:</label><br>
                        <select class="form-control select2" id="student_id" name="student_id">
                            <option value="">Please select academic session first</option>
                        </select>
                    </div>                                                                                                  
                </div>                
                </div>   
                <div id="ajax-student-result-div">                    
                </div>
                @if(!is_null($enrolled_students))
                    @if(count($enrolled_students))
                    <?php $i=1; ?>                    
                        <div class="table-responsive normal-student-result-div">
                            <table class="table table-bordered table-hover" id="result-table">   
                                <thead>
                                    <tr style="background-color:#333; color:white;">
                                    <th>SN</th>
                                    <th>Student Name / ID</th>                     
                                    <th>Status</th>                                                     <th>Action</th>                 
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrolled_students as $index => $record)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$record->name .' / '. $record->uniqueId}}                                  
                                        </td>
                                        <td>
                                            @if(count((new \Modules\Result\Entities\Result)->checkResultPublished($record->student_id, $selected_session_id)))
                                            <span class="bg-primary" style="padding: 3px;">Published</span>
                                            @else
                                            <span style="padding: 3px; background: #eb4e4e; color:white;">not published</span>
                                            @endif                                            
                                        </td>
                                        <td>
                                            @if(count((new \Modules\Result\Entities\Result)->checkResultPublished($record->student_id, $selected_session_id)))
                                            
                                            <a href="{{route('view-results',[$selected_session_id, $record->student_id])}}" class="btn btn-primary btn-flat" data-lity>View Results</a>                                                     
                                            <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#deleteResult{{$record->student_id}}" data-title="Delete Result" data-message="Are you sure you want to delete this user ?">
                                             <i class="glyphicon glyphicon-trash"></i> 
                                            </a>            
                                            @include('result::modal.result-delete-modal')                         
                                            @else
                                            <span style="padding: 3px; background: #eb4e4e; color:white;">Action unavailable</span>
                                            @endif
                                        </td>
                                    </tr>                                    
                                    @endforeach             
                                </tbody>                                    
                            </table>
                        </div>                                  
                    @else
                    <div class="alert alert-danger alert-dismissable">
                        <h4><i class="icon fa fa-warning"></i>Enrolled student not available</h4>
                    </div>  
                    @endif                
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
<script type="text/javascript" src="{{asset('public/sms/assets/custom-js/update-enrolled-student-function.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/assets/js/lity.min.js')}}"></script>
<script type="text/javascript">

    const config = {
        routes: {
            updateCourseRoute: '{{route("get-courses-from-course-type")}}',
            getEnrolledStudentListRoute:'{{route("ajax-get-enrolled-student-from-session")}}'                                 
        }
    };    
    
    $(document).ready(function() {

        $('.select2').select2();
        /* Toggling Divs Start */
        $('.select-student-div').hide();
        $('#ajax-student-result-div').hide();
        
        $('#switch-search').on('click', function(e) {
            e.preventDefault();
            toggleDivs();           

        });

        function toggleDivs()
        {
            if($('.select-student-div').is(":hidden"))
            {
                $('.select-student-div').show();
                $('.select-div').hide();
                $('#ajax-student-result-div').show();
                $('.normal-student-result-div').hide();           
            }
            else
            {
                $('.select-student-div').hide();
                $('.select-div').show();
                $('.normal-student-result-div').show();
                $('#ajax-student-result-div').hide();
                
            }
        }

        if($('#course_type_id').val() != 0)
        {
            updateCourse($('#course_type_id').val());
        }

        $('#result-table').DataTable();

        $('#session_id').on('change', function() {

            if($('#session_id').val() && $('#course_type_id').val() && $('#course_id').val())
            {
                updateEnrolledStudents();
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
                updateEnrolledStudents();
            }
            else
            {
                toastr.info('Please select all option fields');
            }
        });

        function updateEnrolledStudents()
        {
            let current_url = $('#current_url').val(); 
            current_url += '?session_id=' + $('#session_id').val() + '&course_type_id=' + $('#course_type_id').val() + '&course_id=' + $('#course_id').val();
            window.location.replace(current_url);
            
        }

        if($('#secondary_session_id').val())
        {
            
            updateStudentList($('#secondary_session_id').val());
        }


        $('#secondary_session_id').on('change', function() {            
            let session_id = $('#secondary_session_id').val();
            updateStudentList(session_id);            
        })

        $('#student_id').on('change', function() {
            let session_id = $('#secondary_session_id').val();
            let student_id = $('#student_id').val();
            $.ajax({
                url: '{{route("ajax-get-individual-student-result")}}', 
                data: {
                    session_id, 
                    student_id
                }, 
                type: 'GET', 
                success:function(response){
                    $('#ajax-student-result-div').html(response);
                }, 
                error: function (jqXHR, textStatus){
                    toastr.error(textStatus);
                }
            })
        })
    });
    
</script>
@endsection
