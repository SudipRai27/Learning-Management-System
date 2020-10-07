<?php 
  //url for the tabs
  $tabs = array(
                array('url' => URL::route('list-assignment'),
                      'alias' => 'List Assignment'),   
                array('url' => URL::route('create-assignment'),
                      'alias' => 'Create Assignment'),                             
                array('url' => URL::route('view-assignment-submission-backend'),
                      'alias' => 'View Submissions'),
              
                array('url' => URL::route('list-assignment-marks'),
                      'alias' => 'List Assignment Marks'),                                     
                array('url' => URL::route('upload-assignment-marks'),
                      'alias' => 'Upload Assignment Marks')
                );
?>


<div class="nav-tabs-custom">            
    <ul class="nav nav-tabs">
      @foreach($tabs as $tab)
        <li @if(Request::url() == $tab['url']) class="active" @endif><a href="{{$tab['url']}}">{{$tab['alias']}}</a></li>
      @endforeach
     
    </ul>
</div>

