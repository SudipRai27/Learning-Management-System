<?php 
  //url for the tabs
  $tabs = array(
                array('url' => URL::route('list-timetable'),
                      'alias' => 'List TimeTable'),   
                array('url' => URL::route('create-timetable'),
                      'alias' => 'Create / Update TimeTable'),             
                                                                        
                );
?>


<div class="nav-tabs-custom">            
    <ul class="nav nav-tabs">
      @foreach($tabs as $tab)
        <li @if(Request::url() == $tab['url']) class="active" @endif><a href="{{$tab['url']}}">{{$tab['alias']}}</a></li>
      @endforeach
     
    </ul>
</div>

