<?php 
  //url for the tabs
  $tabs = array(
                array('url' => URL::route('list-exam'),
                      'alias' => 'List Exam'),   
                array('url' => URL::route('create-exam'),
                      'alias' => 'Create Exam'),             
                array('url' => URL::route('list-exam-marks'),
                      'alias' => 'List Exam Marks'),             
                array('url' => URL::route('upload-exam-marks'),
                      'alias' => 'Upload Exam Marks'),             
                                               
                );
?>


<div class="nav-tabs-custom">            
    <ul class="nav nav-tabs">
      @foreach($tabs as $tab)
        <li @if(Request::url() == $tab['url']) class="active" @endif><a href="{{$tab['url']}}">{{$tab['alias']}}</a></li>
      @endforeach
     
    </ul>
</div>

