@if(count($data))
<div class="row">
	<div class="col-md-12">		
		<div class="row">	
			@foreach($data as $index => $record)
				<div class="col-sm-3 col-md-2 col-lg-3  cards">					
					<div class="card">
						<div class="card-h">
							{{$record->subject_name}}
						</div>
						<div class="card-heading">
							
						</div>						
						<input type="hidden" class="subject_id" value="{{$record->id}}">
						<button class="btn btn-success btn-flat dashboard-subject-btn">View</button>
					</div>
				</div>	
			@endforeach
		</div>
	</div>
</div>
@else
<div class="alert alert-danger alert-dismissable">
    <h4><i class="icon fa fa-warning"></i>Sorry Enrollment Subjects Could not be found</h4>
</div>  
@endif


