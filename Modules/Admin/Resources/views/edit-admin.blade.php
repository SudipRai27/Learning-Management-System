@extends('backend.main')
@section('custom-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />    
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    <h4><b>Admin Manager</b></h4>    
    @include('admin::tabs')
    <a href="{{route('list-admin')}}" type="button" class="btn btn-danger flat">Go Back</a><br><br>
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
                <div class="form-header"><p class="form-header">Edit Admin</p></div>
                <form action="{{route('update-admin', $admin->id)}}" method="POST" enctype="multipart/form-data" class="crud-form">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label> Admin Name: </label>
                                <input type="text" name="name" id = "name" class="form-control @if($errors->first('name')) form-error @endif" placeholder="Admin Name" value="{{$admin->name}}">
                            </div>
                            <div class="col-sm-6">
                                <label>Admin Email: </label>
                                <input type="email" name="email" id = "email" class="form-control @if($errors->first('email')) form-error @endif" placeholder="Admin email" value="{{$admin->email}}">
                            </div>                            
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>DOB:</label>
                                <div id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
                                <input class="form-control @if($errors->first('dob')) form-error @endif" type="text" readonly name="dob" id="dob" value="{{$admin->dob}}">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Address:</label>
                                <input type="text" name="address" id = "address" class="form-control @if($errors->first('address')) form-error @endif" placeholder="Admin address" value="{{$admin->address}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label> Admin phone: </label>
                                <input type="text" name="phone" id = "phone" class="form-control @if($errors->first('phone')) form-error @endif" placeholder="Admin phone" value="{{$admin->phone}}">
                            </div>
                            <div class="col-sm-6">
                                <label>Emergency contact name: </label>
                                <input type="text" name="emergency_contact_name" id = "emergency_contact_name" class="form-control @if($errors->first('emergency_contact_name')) form-error @endif" placeholder="Admin emergency contact name" value="{{$admin->emergency_contact_name}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Emergency contact number: </label>
                                <input type="text" name="emergency_contact_number" id = "emergency_contact_number" class="form-control @if($errors->first('emergency_contact_number')) form-error @endif" placeholder="Admin emergency contact number" value="{{$admin->emergency_contact_number}}">
                            </div>
                            <div class="col-sm-6">
                                <label>Image:</label>
                                <input type="file" name="photo" class="form-control @if($errors->first('photo')) form-error @endif">
                                                            
                            </div>
                        </div>
                        <br>
                            @if($admin->photo)        
                            <div class="col-sm-6">
                                <b>Current Image: </b><br>
                                <img src="{{url('/modules/admin/resources/assets/images/'.$admin->photo)}}" height="100px" width="100px">             
                            </div>
                            @endif  
                        </div>    
                        &nbsp;&nbsp;             
                        <input type="hidden" name="admin_id" value="{{$admin->id}}">
                        <input type="submit" name="" value="Update" class="btn btn-primary flat">
                        <br><br>
                    </div>     
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
    });

</script>
@endsection