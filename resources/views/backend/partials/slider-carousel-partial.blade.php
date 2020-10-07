<?php
	 $slider = (new \Modules\Slider\Entities\Slider)->getFrontendSlider();
   // echo '<pre>';
   // print_r($slider);
   // die();
?>
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      @foreach($slider as $index => $row)
      <li data-target="#myCarousel" data-slide-to="{{$index}}" class="@if($index == 0) active @endif"></li>
      @endforeach
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      @foreach($slider as $index => $d)
      <div class="item @if($index == 0) active @endif" data-toggle="modal" data-target="#viewSlider{{$d->id}}" style="cursor: pointer;">
        <img src="{{$d->s3_url}}" alt="" style="width:100%;">
        <div class="carousel-caption" style= "background: rgba(0,0,0,0.4) !important;">
          <h4>{{$d->title}}</h4>
        </div>
      </div>
        @include('slider::modal.slider-view-modal')           
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
