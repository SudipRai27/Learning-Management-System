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
                <div class="form-header"><p class="form-header">Generate Result </p></div>
                <br>
                <div class="row">
                    <div class="col-sm-4">
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
                    <div class="col-sm-4">
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
                    <div class="col-sm-4">
                        <label>Program: </label>
                        <select class="form-control select2" id="course_id" name="course_id">
                            <option value="0">Please select course type first</option>
                        </select>                    
                    </div>                                        
                </div>                
                </div>   
                @if(!is_null($enrolled_students))
                    @if(count($enrolled_students))
                    <?php $i=1; ?>
                    <form action="{{route('generate-results-post')}}" method="POST">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="result-table">   
                                <thead>
                                    <tr style="background-color:#333; color:white;">
                                    <th>SN</th>
                                    <th>Student Name / ID</th>                     
                                    <th>Status</th>                                                                      
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
                                            <input type="hidden" name="student_id[]" value="{{$record->student_id}}">
                                            <input type="hidden" name="enrollment_id[]" value="{{$record->enrollmentId}}">
                                            <input type="hidden" name="studentEnrollmentId[{{$record->student_id}}][]" value="{{$record->enrollmentId}}">
                                        </td>
                                        
                                    </tr>                                    
                                    @endforeach             
                                </tbody>                                    
                            </table>
                        </div>          
                        <input type="hidden" name="session_id" value="{{$selected_session_id}}">
                        {{csrf_field()}}
                        <button type="submit" name="" class="btn btn-success btn-flat">Publish</button>      
                    </form>
                    @else
                    <div class="alert alert-danger alert-dismissable">
                        <h4><i class="icon fa fa-warning"></i>Enrolled student not available</h4>
                    </div>  
                    @endif
                @else
                <div class="alert alert-warning alert-dismissable">
                    <h4><i class="icon fa fa-warning"></i>Please select academic session, course type and course</h4>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">

    const config = {
        routes: {
            updateCourseRoute: '{{route("get-courses-from-course-type")}}',                         
        }
    };    
    
    $(document).ready(function() {

        $('.select2').select2();

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
    });
    
</script>
@endsection
