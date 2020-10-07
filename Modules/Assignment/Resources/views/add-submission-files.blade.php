@extends('backend.submain')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
<link href="{{ asset('public/sms/assets/css/fileinput.css') }}" media="all" rel="stylesheet" type="text/css"/>
<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous"> -->
<link href="{{ asset('public/sms/assets/css/theme.css') }}" media="all" rel="stylesheet" type="text/css"/>

@endsection
@section('content')
<div class="form-header"><p class="form-header">Submitted Resources</p></div>
@include('backend.partials.errors')
<div class="table-responsive">
<table class="table table-bordered table-hover" id="lecture-table">   
	<thead>
		<tr style="background-color:#333; color:white;">
		<th>SN</th>
		<th>File</th>							
		<th>Action</th>
		</tr>
	</thead>
	@if(count($submission_resources) > 0)
	<tbody>
		<?php $i = 1;?>
		@foreach($submission_resources as $index => $resource)						
		<tr>
		<td>{{$i++}}</td>
		<td>
			<a href="{{$resource['s3_url']}}">{{$resource['filename']}}			
		</td>								
		<td>
			<form action="{{route('remove-assignment-submission-resource', $resource['resource_id'])}}" method="POST">
				<input type="submit" name="" value="Remove" class="btn btn-danger btn-flat">
				{{csrf_field()}}
			</form>
		</td>							
		</tr>
		@endforeach
	</tbody>   
	@endif    														
</table>
<div class="form">
	<p class="form-header2">Upload Assignment</p>
	<form action="{{route('submit-assignment-files')}}" enctype="multipart/form-data" method="POST">
		<div class="form-group">	                    
		    <label>Multiple Files: (Allowed: pptx,txt,doc,pdf,docx: max 5MB each)</label>            
		    <input id="kv-explorer" type="file" name="files[]" class="@if($errors->first('files')) form-error @endif" multiple>
		    <input type="hidden" name="assignment_id" value="{{$assignment_id}}">
		    <input type="submit" name="Upload" value="Upload" class="btn btn-success btn-flat">
		    {{csrf_field()}}
		</div>
	</form>
</div>
@endsection
@section('custom-js')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('public/sms/assets/js/fileinput.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/sms/assets/js/theme.js') }}" type="text/javascript"></script>
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