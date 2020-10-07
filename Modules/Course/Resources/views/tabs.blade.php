<?php 
  //url for the tabs
  $tabs = array(
                array('url' => URL::route('list-course-type'),
                      'alias' => 'List Course Type'),   
                array('url' => URL::route('create-course-type'),
                      'alias' => 'Create Course Type'),             
                array('url' => URL::route('list-course'),
                      'alias' => 'List Course'),   
                array('url' => URL::route('create-course'),
                      'alias' => 'Create Course')                                                        
                );
?>


<div class="nav-tabs-custom">            
    <ul class="nav nav-tabs">
      @foreach($tabs as $tab)
        <li @if(Request::url() == $tab['url']) class="active" @endif><a href="{{$tab['url']}}">{{$tab['alias']}}</a></li>
      @endforeach
     
    </ul>
</div>

