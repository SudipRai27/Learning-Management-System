@extends('backend.main')
@section('custom-css')
<style type="text/css">
    .container {
    max-width: 960px;
    }

    .panel-default>.panel-heading {
      color: #333;
      background-color: #fff;
      border-color: #e4e5e7;
      padding: 0;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    .panel-default>.panel-heading a {
      display: block;
      padding: 10px 15px;
    }

    .panel-default>.panel-heading a:after {
      content: "";
      position: relative;
      top: 1px;
      display: inline-block;
      font-family: 'Glyphicons Halflings';
      font-style: normal;
      font-weight: 400;
      line-height: 1;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      float: right;
      transition: transform .25s linear;
      -webkit-transition: -webkit-transform .25s linear;
    }

    .panel-default>.panel-heading a[aria-expanded="true"] {
      background-color: #e31e4c;
      color: #fff;
    }

    .panel-default>.panel-heading a[aria-expanded="true"]:after {
      content: "\2212";
      -webkit-transform: rotate(180deg);
      transform: rotate(180deg);
    }

    .panel-default>.panel-heading a[aria-expanded="false"]:after {
      content: "\002b";
      -webkit-transform: rotate(90deg);
      transform: rotate(90deg);
    }

    .accordion-option {
      width: 100%;
      float: left;
      clear: both;
      margin: 15px 0;
    }

    .accordion-option .title {
      font-size: 20px;
      font-weight: bold;
      float: left;
      padding: 0;
      margin: 0;
    }

    .accordion-option .toggle-accordion {
      float: right;
      font-size: 16px;
      color: #6a6c6f;
    }
  
    .grade-table td a {
      color: black !important;
      cursor:pointer;      
    }

    .grade-table td a:hover {
      text-decoration: underline;
    }

    .grades h4 {
      background: #d4c6c6;

    }    
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
    <h4><b>Grades</b></h4>
        @include('result::grades-tab')
        <div class="box"> 
            <div class="box-body">                          
                @if ($errors->any())
                <div class = "alert alert-danger alert-dissmissable">
                <button type = "button" class = "close" data-dismiss = "alert">X</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(count($student_result))
                <div class="panel-group" id="accordion">
                  <?php
                    $i = 1;
                  ?>
                  @foreach($student_result as $index => $result)
                  <div class="panel panel-default">
                      <div class="panel-heading grades">
                          <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$i}}">
                            {{$result['session_name']}}</a>
                          </h4>
                      </div>
                      <div id="collapse{{$i}}" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover grade-table">
                                    <thead>
                                        <tr style="background:#252551; color:white;">
                                            <th>Subject</th>                
                                            <th>Total Obtained Marks</th>
                                            <th>Total Assessable Marks</th>
                                            <th>Grade</th>
                                        </tr>
                                        <tbody>
                                            @foreach($result['results'] as $index => $record)
                                            <tr>                            
                                                <td>
                                                    <a data-toggle="modal" data-target="#viewSubjectAssessment{{$result['session_id']}}_{{$record->subject_id}}" data-title="View Assessment details" data-message="View Assessment">
                                                    {{$record->subject_name}}
                                                    </a> 
                                                    @include('result::modal.view-grades-modal') 
                                                    </td>                                    
                                                <td>{{$record->total_obtained_marks}}</td>
                                                <td>{{$record->total_assessable_marks}}</td>                
                                                <td>{{$record->grade}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </thead>
                                </table>
                            </div>
                        </div>
                      </div>
                  </div>
                  <?php
                    $i++
                  ?>
                  @endforeach
                  <!-- <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse in" data-parent="#accordion" href="#collapse3">
                        Collapsible Group 3</a>
                      </h4>
                    </div>
                    <div id="collapse3" class="panel-collapse collapse">
                      <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                      minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                      commodo consequat.</div>
                    </div> -->
                </div>
                @else
                <div class="alert alert-danger alert-dismissable">
                    <h4><i class="icon fa fa-warning"></i>Results Not Available</h4>
                </div>  
                @endif
            </div>             
        </div>
    </div>
</div>
@endsection
@section('custom-js')
<script type="text/javascript">
    $(document).ready(function() {

  $(".toggle-accordion").on("click", function() {
    var accordionId = $(this).attr("accordion-id"),
      numPanelOpen = $(accordionId + ' .collapse.in').length;
    
    $(this).toggleClass("active");

    if (numPanelOpen == 0) {
      openAllPanels(accordionId);
    } else {
      closeAllPanels(accordionId);
    }
  })

  openAllPanels = function(aId) {
    console.log("setAllPanelOpen");
    $(aId + ' .panel-collapse:not(".in")').collapse('show');
  }
  closeAllPanels = function(aId) {
    console.log("setAllPanelclose");
    $(aId + ' .panel-collapse.in').collapse('hide');
  }
     
});
</script>
@endsection
