<?php 
  //url for the tabs
  $tabs = array(
                array('url' => URL::route('list-teacher'),
                      'alias' => 'List Teacher'),   
                array('url' => URL::route('create-teacher'),
                      'alias' => 'Create Teacher '),             
                array('url' => URL::route('list-assigned-teacher'),
                      'alias' => 'List Assigned Teacher '),             
                array('url' => URL::route('assign-teacher'),
                      'alias' => 'Assign Teacher '),             
                                           
                );
?>


<div class="nav-tabs-custom">            
    <ul class="nav nav-tabs">
      @foreach($tabs as $tab)
        <li @if(Request::url() == $tab['url']) class="active" @endif><a href="{{$tab['url']}}">{{$tab['alias']}}</a></li>
      @endforeach
     
    </ul>
</div>

