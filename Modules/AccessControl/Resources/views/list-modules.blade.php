@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/table.css')}}">
<style type="text/css">
.modules-table a{
	color:black !important;
	font-size: 1.6rem !important;
	font-style: italic;
}

.modules-table a:hover{
	text-decoration: underline !important;
	color: black !important;		
}

.table-heading {
	background: #098668;
	color: white;
	width: 90%;
	margin: 1rem auto;
	padding: 1.5rem;
	font-size: 1.9rem;
	text-align: center;
	
}
</style>
@endsection
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>&nbsp;&nbsp;&nbsp;&nbsp;Modules</b></h4>	
		<div class="box"> 
			<div class="box-body">
				<div class = 'content'>				
					<div class="table-responsive">
						<h3 class="table-heading">Set you permission here according to modules</h3>
						<table class = 'table table-striped table-hover table-bordered modules-table'>
							<tbody class = 'search-table'>
							@if(count($modules))
							<?php $i = 1; ?>
							@foreach($modules as $d)
								<tr>
								<td>{{$i++}}</td>
								<td><a href = "{{route('create-permission', $d)}}">{{$d}}</a></td>
								</tr>
							@endforeach
							@else
								<tr>
								<td>No Modules Found</td>
								</tr>
							@endif
							</tbody>
						</table>
					</div>
				</div>			
			</div>
		</div>
	</div>
</div>
@endsection

