@extends('backend.main')
@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />    
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    <h4><b>Student Manager</b></h4>
    <!-- @include('student::tabs') -->
    <a href="{{route('list-student')}}" type="button" class="btn btn-danger flat">Go Back</a><br><br>
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
                <div class="alert alert-warning alert-dismissable">
                    <p><i class="icon fa fa-warning"></i>Before editing student please make sure whether the student has any enrollment for the current session.</p>
                </div>                  
                <div class="form-header"><p class="form-header">Edit Student</p></div>
                <form action="{{route('update-student', [$student->id, $student->user_id])}}" method="POST" enctype="multipart/form-data" class="crud-form">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label> Student Name: </label>
                                <input type="text" name="name" id = "name" class="form-control @if($errors->first('name')) form-error @endif" placeholder="Student Name" value="{{$student->name}}">
                            </div>
                            <div class="col-sm-6">
                                <label>Student Email: </label>
                                <input type="email" name="email" id = "email" class="form-control @if($errors->first('email')) form-error @endif" placeholder="Student email" value="{{$student->email}}">
                            </div>
                            <!-- <div class="col-sm-4">
                                <label>Student ID: </label>
                                <input type="text" name="student_id" id = "student_id" class="form-control @if($errors->first('student_id')) form-error @endif" placeholder="Student ID" value="{{Input::old('student_id')}}">
                            </div> -->
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>DOB:</label>
                                <div id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
                                <input class="form-control @if($errors->first('dob')) form-error @endif" type="text" readonly name="dob" id="dob" value="{{$student->dob}}">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Address:</label>
                                <input type="text" name="address" id = "address" class="form-control @if($errors->first('address')) form-error @endif" placeholder="Student address" value="{{$student->address}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Program / Course Type:</label>
                                <select id="course_type_id" name="course_type_id" class="form-control @if($errors->first('course_type_id')) form-error @endif" >
                                    <option value="">Select</option>
                                    @foreach($course_type as $index => $d)
                                    <option value="{{$d->id}}" @if($d->id == $student->current_course_type_id) selected @endif>{{$d->course_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Course: </label>
                                <select id="course_id" name="course_id" class="form-control @if($errors->first('course_id')) form-error @endif">
                                    <option value="">Select</option>
                                    @foreach($courses as $index => $d)
                                    <option value="{{$d->id}}" @if($d->id == $student->current_course_id) selected @endif>{{$d->course_title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label> Student phone: </label>
                                <input type="text" name="phone" id = "phone" class="form-control @if($errors->first('phone')) form-error @endif" placeholder="Student phone" value="{{$student->phone}}">
                            </div>
                            <div class="col-sm-6">
                                <label>Emergency contact name: </label>
                                <input type="text" name="emergency_contact_name" id = "emergency_contact_name" class="form-control @if($errors->first('emergency_contact_name')) form-error @endif" placeholder="Student emergency contact name" value="{{$student->emergency_contact_name}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Emergency contact number: </label>
                                <input type="text" name="emergency_contact_number" id = "emergency_contact_number" class="form-control @if($errors->first('emergency_contact_number')) form-error @endif" placeholder="Student emergency contact number" value="{{$student->emergency_contact_number}}">
                            </div>
                            <div class="col-sm-6">
                                <label>Image:</label>
                                <input type="file" name="photo" class="form-control @if($errors->first('photo')) form-error @endif">
                                                            
                            </div>
                        </div>
                            <br>
                        @if($student->photo)
                        <div class="row"> 
                            <div class="col-sm-6">
                            <b>Current Image: </b><br>
                            <img src="{{url('/modules/student/resources/assets/images/'.$student->photo)}}" height="100px" width="100px">                            
                            </div>
                        </div>
                        @endif      
                        <br>
                        <input type="submit" name="" value="Update" class="btn btn-primary flat">                      
                        </div>    
                        <!-- <input type="hidden" name="role_id" value="3"> -->
                {{ csrf_field() }}
                </form>
                
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    $(function () {
    $("#datepicker").datepicker({ 
        autoclose: false, 
        todayHighlight: false,
        format: 'yyyy-mm-dd'
        }).datepicker( new Date);
    });
    

    $(function() {
        $('#name').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#email').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#address').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#dob').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#phone').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#dob').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#photo').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
         $('#emergency_contact_name').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#emergency_contact_number').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 

        $('#course_type_id').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#course_id').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 

    });

    $('#course_type_id').change(function(){
        const selected_course_id = '';
        updateCourseList(selected_course_id);
    });

    function updateCourseList(selected_course_id)
    {
        const course_type_id = $('#course_type_id').val();
        $('#course_id').html('<option>Loading....</option>');
        $.ajax({
            type: 'GET', 
            url: '{{ route('get-courses-from-course-type')}}', 
            data: {
                course_type_id: course_type_id,
                selected_course_id : selected_course_id
                },
            success:function(data){
                $('#course_id').html(data);
            }
        });
    }

</script>
@endsection