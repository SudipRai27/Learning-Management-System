<?php 
  //url for the tabs
  $tabs = array(
                array('url' => URL::route('list-event'),
                      'alias' => 'List Event'),   
                array('url' => URL::route('create-event'),
                      'alias' => 'Create Event'),             
                                               
                );
?>


<div class="nav-tabs-custom">            
    <ul class="nav nav-tabs">
      @foreach($tabs as $tab)
        <li @if(Request::url() == $tab['url']) class="active" @endif><a href="{{$tab['url']}}">{{$tab['alias']}}</a></li>
      @endforeach
     
    </ul>
</div>

