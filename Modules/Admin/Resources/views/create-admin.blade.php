@extends('backend.main')
@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />    
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css">

@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    <h4><b>Teacher Manager</b></h4>
    @include('admin::tabs')
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
                <div class="form-header"><p class="form-header">Create Admin</p></div>
                <form action="{{route('create-admin-post')}}" method="POST" enctype="multipart/form-data" class="crud-form">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label> Admin Name: </label>
                                <input type="text" name="name" id = "name" class="form-control @if($errors->first('name')) form-error @endif" placeholder="Name" value="{{Input::old('name')}}">
                            </div>
                            <div class="col-sm-6">
                                <label> Admin Email: </label>
                                <input type="email" name="email" id = "email" class="form-control @if($errors->first('email')) form-error @endif" placeholder="Email" value="{{Input::old('email')}}">
                            </div>                           
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>DOB:</label>
                                <div id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
                                <input class="form-control @if($errors->first('dob')) form-error @endif" type="text" readonly name="dob" id="dob" value="{{Input::old('dob')}}">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Address:</label>
                                <input type="text" name="address" id = "address" class="form-control @if($errors->first('address')) form-error @endif" placeholder="Address" value="{{Input::old('address')}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label> Phone: </label>
                                <input type="text" name="phone" id = "phone" class="form-control @if($errors->first('phone')) form-error @endif" placeholder="Phone" value="{{Input::old('phone')}}">
                            </div>
                            <div class="col-sm-6">
                                <label>Emergency contact name: </label>
                                <input type="text" name="emergency_contact_name" id = "emergency_contact_name" class="form-control @if($errors->first('emergency_contact_name')) form-error @endif" placeholder="Emergency contact name" value="{{Input::old('emergency_contact_name')}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Emergency contact number: </label>
                                <input type="text" name="emergency_contact_number" id = "emergency_contact_number" class="form-control @if($errors->first('emergency_contact_number')) form-error @endif" placeholder="Emergency contact number" value="{{Input::old('emergency_contact_number')}}">
                            </div>
                            <div class="col-sm-6">
                                <label>Image:</label>
                                <input type="file" name="photo" class="form-control @if($errors->first('photo')) form-error @endif">
                            </div>
                        </div>    
                        <br>
                        <input type="hidden" name="role_id" value="1">
                        <input type="submit" name="" value="Create" class="btn btn-primary flat">  

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
        $('#emergency_contact_number').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#emergency_contact_name').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
    });

</script>
@endsection