<?php 
  //url for the tabs
  $tabs = array(
                array('url' => URL::route('list-academic-session'),
                      'alias' => 'List Academic Session'),   
                array('url' => URL::route('create-academic-session'),
                      'alias' => 'Create Academic Session'),             
                array('url' => URL::route('academic-session-settings'),
                      'alias' => 'Settings'),             
                                               
                );
?>


<div class="nav-tabs-custom">            
    <ul class="nav nav-tabs">
      @foreach($tabs as $tab)
        <li @if(Request::url() == $tab['url']) class="active" @endif><a href="{{$tab['url']}}">{{$tab['alias']}}</a></li>
      @endforeach
     
    </ul>
</div>

