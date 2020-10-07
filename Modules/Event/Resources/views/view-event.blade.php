@extends('backend.main')
@section('content')
<div class="row">
	<div class="col-xs-12">
	<h4><b>Event Manager</b></h4>	
	@include('event::tabs')	
		<div class="box"> 
			<div class="box-body">								
				<div class="row event">
					<div class="col-sm-12 col-md-4 event-col1">
						<div class="event-details">
							<div class="title">
								<i class="fa fa-thumb-tack" aria-hidden="true"></i>
								{{$event->event_title}}</div>
							<div class="location">
								<i class="fa fa-map-marker" aria-hidden="true"></i>
								{{$event->location}}</div>
							<div class="date">

								<i class="fa fa-calendar" aria-hidden="true"></i>
								<span class="primary-badge"> 
									{{date('Y-M-d',strtotime($event->start_date))}} to {{date('Y-M-d',strtotime($event->end_date))}} </span>
							</div>
							<div class="time">
								<i class="fa fa-clock-o" aria-hidden="true"></i>
								<span class="success-badge">
									{{date('h:i A',strtotime($event->start_time))}} - {{date('h:i A',strtotime($event->end_time))}} </span>
							</div>	
						</div>						
					</div>
					<div class="col-sm-12 col-md-8 event-col2">
						<div class="event-carousel">
							@if(count($event->resources) > 0)
							<div id="myCarousel" class="carousel slide" data-ride="carousel">
							  	<!-- Indicators -->
								<ol class="carousel-indicators">
								  	@foreach($event->resources as $index => $resource)
								    <li data-target="#myCarousel" data-slide-to="{{$index}}" class="@if($index == 0) active @endif"></li>
								    @endforeach
								</ol>

							  	<div class="carousel-inner">
							  		@foreach($event->resources as $index => $resource)
							    	<div class="item @if($index == 0) active @endif">
							      		<img src="{{$resource['s3_url']}}">
							    	</div>
							    	@endforeach
							  	</div>
							  	<!-- Left and right controls -->
							  	<a class="left carousel-control" href="#myCarousel" data-slide="prev">
							    	<span class="glyphicon glyphicon-chevron-left"></span>
							    	<span class="sr-only">Previous</span>
							  	</a>
							  	<a class="right carousel-control" href="#myCarousel" data-slide="next">
							    	<span class="glyphicon glyphicon-chevron-right"></span>
							    	<span class="sr-only">Next</span>
							  	</a>
							</div>
							@endif
						</div>
						<div class="event-description">
							{!!$event->description!!}
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>
</div>
@endsection
@section('custom-js')

@endsection