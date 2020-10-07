<div class="table-responsive" id="search-results">
	@if(count($subjects))
		<?php  $i =1; ?>						
    <table class="table table-bordered table-hover">        
		<thead>
			<tr style="background-color:#333; color:white;">
			<th>SN</th>
			<th>Subject Name</th>							
			<th>Is Graded</th>
			<th>Credit Points</th>
			
			<th>Action</th>
			</tr>
		</thead>
		<tbody>
		@foreach($subjects as $index => $d)
			<tr>
			<td>{{$i++}}</td>
			<td>{{$d->subject_name}}</td>
			<td>{{$d->is_graded }}</td>
			<td>{{$d->credit_points }}</td>
			
			<td>				
				<a data-toggle="modal" data-target= "#viewSubject{{$d->id}}" data-title="View Subject" class="btn btn-success btn-flat" > <i class="fa fa-fw fa-file"></i></a>
				@include('subject::subject-view-modal')				
				<a href = "{{route('edit-subjects', $d->id)}}"><button data-toggle="tooltip" title="" class="btn btn-primary btn-flat" type="button" data-original-title="Edit"> <i class="fa fa-fw fa-edit"></i></button></a>
                <a href = "{{route('delete-subject', $d->id)}}" onclick="return ConfirmDelete();"><button data-toggle="tooltip" title="" class="btn btn-danger btn-flat" type="button" data-original-title="Delete"> <i class="fa fa-fw fa-trash"></i></button></a>
			</td>
			</tr>
		@endforeach
		</tbody>       								
    </table>
	@else
	<div class="alert alert-warning alert-dismissable">
			<h4><i class="icon fa fa-warning"></i>NO SUBJECTS AVAILABLE</h4>
		</div>	
	@endif						
</div>