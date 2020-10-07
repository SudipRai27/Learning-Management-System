@extends('backend.main')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/fileinput.css') }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/sms/assets/css/theme.css') }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 
<style type="text/css">
	.form-header2 {
        background-color: orange;
        margin:10px 0px;
        height:35px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        padding: 6px;
	}

	.error-badge {
		display: inline-block;
		margin: 1rem 0;
	}
</style>
@endsection
@section('content')
<h4><b>Slider Manager</b></h4>		
@include('slider::tabs')
<div class="form-header"><p class="form-header">Create Slider</p></div>
<form action="{{route('create-slider-post')}}" method="POST" enctype="multipart/form-data">
	<div class="box-body">
		@include('backend.partials.errors')
		<div class="form-header2">Create</div>
		<div class="form-group">
			<label>Title: </label>
				<input type="text" name="title" id ="title" class="form-control" value="{{ Input::old('title')}}" placeholder="Title" required>
				@if($errors->first('title'))  
				<span class="error-badge">{{$errors->first('title')}} </span>
				@endif
			</div>
		<div class="form-group">
			<b>Description:</b>
			<textarea id="mytextarea" name="description" class="form-control" placeholder="Description" rows="20" required>
      			{{ Input::old('description')}}
    		</textarea>
    		@if($errors->first('description'))
    		<span class="error-badge">{{$errors->first('description')}}</span>
    		@endif
		</div>		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">	
					<label>Sort order:</label>
		        	<input id="sort_order" class="form-control" type="number" placeholder="Sort Order" name="sort_order" value="{{Input::old('sort_order')}}" min="1" required>
		        	@if($errors->first('sort_order'))  
		        	<span class="error-badge">{{$errors->first('sort_order')}}</span>
		        	@endif 
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label>Featured Image (* Allowed: jpg,jpeg,png,svg,gif , max: 5048 KB)</label>
					<input id="featured_image" type="file" name="featured_image[]" required>
       		    	@if($errors->first('featured_image'))  
					<span class="error-badge"> {{$errors->first('featured_image')}}</span>
					@endif 					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label>Is Active: </label>
					<select class="form-control" name="is_active">						
						<option value="yes" @if(Input::old('is_active') == 'yes') selected @endif>Yes</option>
						<option value="no" @if(Input::old('is_active') == 'no') selected @endif>No</option>
					</select>
					@if($errors->first('is_active'))  
					<span class="error-badge"> {{$errors->first('is_active')}}</span>
					@endif 					
				</div>
			</div>
		</div>
		<input type="hidden" name="action" value="create"> 
		<input type="submit" value="Create" class="btn btn-success btn-flat">
		<input type="reset" name="" value="Reset Form" class="btn btn-danger btn-flat">
	</div>
{{ csrf_field() }}
</form>
@endsection
@section('custom-js')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('public/sms/assets/js/fileinput.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/sms/assets/js/theme.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAv95omW2gJppOIwwOPDwft0Ob-D-qANMs&libraries=places"></script>
<script type="text/javascript">
	$(document).ready(function (){

		$('#title').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        $('#mytextarea').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
        
        $('#sort_order').keypress(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
       
        $('#featured_image').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 

        $('#is_active').change(function(){
            $(this).removeClass('form-error')
            $(this).addClass('form-success');
        }); 
	});

</script>
<script src="https://cdn.tiny.cloud/1/cihbjlc54e3wtdrzdj8590rct8sm2ih12lrpjxogi98neu98/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
	tinymce.init({
      selector: '#mytextarea',
      plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
      toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
      toolbar_mode: 'floating',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
    });
</script>
@endsection