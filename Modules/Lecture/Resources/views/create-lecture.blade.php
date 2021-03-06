@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"> 
<link href="{{ asset('public/sms/assets/css/fileinput.css') }}" media="all" rel="stylesheet" type="text/css"/>
<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous"> -->
<link href="{{ asset('public/sms/assets/css/theme.css') }}" media="all" rel="stylesheet" type="text/css"/>
<style type="text/css">
	.fileinput-remove-button, 
	.fileinput-upload-button, 
	.kv-file-upload, 
	.kv-file-zoom, 
	.file-upload-indicator,
	.fa-plus-circle:before{
		display: none;
	}

	.error-msg {
		color:red;
	}
</style>
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Lecture Manager</b></h4>		
		<div class="box"> 
			<div class="box-body">								
				@include('lecture::tabs')				
				<div class="form-header"><p class="form-header">Create Lecture</p></div>	
				<div class="form-header2">Add</div>
				@include('backend.partials.errors')
				<form action="{{route('create-lecture')}}" enctype="multipart/form-data" method="POST">
					<div class="form-group">
						<label>Academic Session: </label>
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
						<p class="error-msg"> @if($errors->first('session_id'))  {{$errors->first('session_id')}}@endif</p> 
					</div>
					<div class="form-group">
						<label>Subject: </label>
						<select class="form-control select2 @if($errors->first('subject_id')) form-error @endif" id="subject_id" name="subject_id">
							@if($role=="admin")
							<option value="">Select</option>							
							@foreach($subjects as $index => $sub)
								<option value="{{$sub->id}}" 
									@if(Input::old('subject_id') == $sub->id)
									selected
									@endif
								>{{$sub->subject_name}} -- {{$sub->course_type}} -- {{$sub->course_title}}</option>
								@endforeach
							@else
							<option value="">Select</option>	
							@endif
						</select>
						<p class="error-msg"> @if($errors->first('subject_id'))  {{$errors->first('subject_id')}}@endif</p> 
					</div>
					</div>
					<div class="form-group">
						<label>Lecture Name: </label>
                        <input type="text" name="lecture_name" id = "lecture_name" class="form-control @if($errors->first('lecture_name')) form-error @endif" placeholder="Lecture Name" value="{{Input::old('lecture_name')}}">                        
					</div>
					<div class="form-group">
						<label>Description:</label> 
						<textarea type="text" name="lecture_description" class="form-control @if($errors->first('lecture_description')) form-error @endif"  placeholder="Description" rows="6">{{ Input::old('lecture_description')}}</textarea>
					</div>
					<div class="form-group">
						<label>Sort Order: </label>
                        <input type="number" name="sort_order" id = "sort_order" class="form-control @if($errors->first('sort_order')) form-error @endif" placeholder="Sort Order" value="{{Input::old('sort_order')}}" min="1">
					</div>					
					<!-- <div class="form-group">
                        <label>Files:</label>
                        <input type="file" name="files[]" id="file-upload-input" class="form-control  @if($errors->first('files')) form-error @endif" multiple>
                        <button class="file-upload-button" type="button">Select File(s)</button>
                        <span class="file-upload-label"></span>
                    </div>			 -->	
                    <div class="form-group">	                    
	           		    <label>Multiple Files: (Allowed: pptx,txt,doc,pdf,docx: max 5MB each)</label>
			                    <!-- <input id="file-0a" class="file" type="file" multiple data-min-file-count="1" data-theme="fas" name="files[]"> -->
	           		    <input type="file" name="files[]" id="file-upload-input" class="@if($errors->first('files')) form-error @endif" multiple>
           			</div>
					<input type="submit" class="btn btn-success btn-flat" value="Create">
					<input type="reset" class="btn btn-danger btn-flat" value="Reset Form">		{{ csrf_field() }}
				</form>								
			</div>
		</div>
	</div>
</div>
<input type="hidden" value="{{$role}}" id="role">	
@if($role == "teacher")
<input type="hidden" value="{{$teacher_id}}" id="teacher_id">
@endif
<input type="hidden" name="" value="{{Input::old('subject_id')}}" id="selected_subject_id">
@endsection
@section('custom-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('public/sms/assets/js/fileinput.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/sms/assets/js/theme.js') }}" type="text/javascript"></script>

<script type="text/javascript">
	$(document).ready(function (){
		$('.select2').select2();

		$('#subject_id').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
		$('#lecture_name').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#lecture_description').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#sort_order').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 

        if($('#role').val() == "teacher")
    	{   		
    		const updateTeacherAssignedSubjectList = (session_id, teacher_id) => {
	    		$('#subject_id').html('<option>Loading</option>');    		
	    		$.ajax({
	    			type : 'GET', 
	    			url: '{{route("ajax-get-teacher-assigned-subjects-from-session")}}', 
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
	});
</script>
<script>
	//File Uploader  	
    $(document).ready(function () {
    	// the file input

    	initializeFileInput();
        
        function initializeFileInput()
        {
	        $("#kv-explorer").fileinput({
	            //'theme': 'explorer-fas',
	            'theme': 'fas',
	            'showPreview': true,
	            'uploadUrl': '#',
	            overwriteInitial: true,
	            initialPreviewAsData: true,   
	            autoReplace: true,  
	            allowedFileExtensions: ["pdf","doc","docx","pptx","txt"],       
	           	removeFromPreviewOnError: true,
        	});
        }
        
       	$('#kv-explorer').click(function() {
       		$(this).fileinput('clear');
   			
        });
		
        
    });
</script>
	
@endsection