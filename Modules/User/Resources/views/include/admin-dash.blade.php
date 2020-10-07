@extends('backend.main')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/assets/css/lity.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('public/sms/plugins/morris/morris.css')}}">
@endsection

@section('content')
<?php 
  $helper_controller = new \App\Http\Controllers\HelperController;
  $total_students = $helper_controller->getTotalStudents();
  $total_teachers = $helper_controller->getTotalTeachers();
  $total_subjects = $helper_controller->getTotalSubjects();
  $total_courses = $helper_controller->getTotalCourses();
  $published_notice = $helper_controller->getPublishedNotice();
  $events = $helper_controller->getFrontendEvents('all');
  $enrollments = $helper_controller->getEnrolledStudentCountBySession();
  
?>
<section class="content-header">
  <h1>
   My Dashboard
  </h1>
</section>
<section class="content">  
  <div class="row">
    <div class="col-lg-3 col-xs-6">      
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>{{$total_courses}}</h3>
          <p>Courses Offered</p>
        </div>
        <div class="icon">
          <i class="ion ion-trophy"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">      
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{$total_subjects}}</h3>
          <p>Subjects</p>
        </div>
        <div class="icon">
          <i class="fa fa-fw fa-envelope"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">      
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{$total_teachers}}</h3>
          <p>Teachers</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-stalker"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">      
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{$total_students}}</h3>
          <p>Total Students</p>
        </div>
        <div class="icon">
          <i class="ion ion-android-contacts"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <section class="col-lg-6">              
      <div class="box box-primary">
        <div class="box-header">
          <i class="ion ion-clipboard"></i>
          <h3 class="box-title">Publish a short notice</h3>
        </div>
        <div class="box-body">
            <ul class="todo-list">
              <li>
                <span class="handle">
                  <i class="fa fa-ellipsis-v"></i>
                </span>
                <span class="text"><a href=""></a></span>
                <small class="label label-danger"><i class="fa fa-clock-o"></i></small>
                <br><br>
                <form action="{{route('publish-notice')}}" method="POST">  
                  <textarea class="form-control" rows="10" required name="notice"></textarea>
                  <br>                  
                  <input type="submit" name="notice-btn" class="btn btn-primary" value="Publish">
                  {{csrf_field()}}
                </form>
              </li>
            </ul>
        </div>
      </div>
      <div class="box box-warning">
        <div class="box-header">
          <i class="fa fa-fw fa-bullhorn"></i>
          <h3 class="box-title">Current Notice</h3>
          <div class="box-body">
            <p></p>
            <p>
              @if(strlen($published_notice['notice']) > 0 && strlen($published_notice['created_at']) > 0)
              <p>{{$published_notice['notice']}}</p>
              <p>{{$published_notice['created_at']}}</p>
              <form action="{{route('delete-notice')}}" method="POST">
                {{csrf_field()}}
                <input type="submit" name="" class="btn btn-danger btn-flat" value="Delete Notice">
              </form>
              @else
              No Current Notice Available
              @endif
            </p>
          </div>
        </div>
      </div>      
    </section>
    <section class="col-lg-6 connectedSortable ui-sortable">
      <div class="box box-info">
        <div class="box-header">
          <i class="fa fa-briefcase"></i>
          <h3 class="box-title">Upcoming Events</h3>
        </div>
        <div class="box-body">
          <ul class="todo-list">
              @foreach($events as $index => $record)
              <li style="margin-bottom: 1rem">
                <div style="margin-bottom: 2rem !important;">
                  <span class="success-badge">
                  {{ date("M d Y", strtotime($record['start_date']))}}
                  </span> 
                  to
                  <span class="error-badge">
                    {{ date("M d Y", strtotime($record['end_date']))}}
                  </span>
                </div>
                <a href="{{route('view-event',['frontend',$record['id']])}}" data-lity><h4 style="color:black;">{{$record['event_title']}}</h4></a>
                <?php
                  if($index == 4)
                  {
                    break;
                  }
                ?>

              </li>
              @endforeach
              <a href="{{route('list-event')}}" style="color:black;"> View All Events <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </ul>
        </div>
      </div>
    </section>
  </div>
  <div class="row">
    <div class="col-sm-12 col-md-6">    
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">General Info</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body chart-responsive">
          <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Enrolled Students</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body chart-responsive">
          <div class="chart" id="bar-chart" style="height: 300px;"></div>
        </div>        
      </div>
    </div>
  </div>
</section>
@endsection
@section('custom-js')
<script type="text/javascript" src="{{asset('public/sms/assets/js/lity.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script type="text/javascript" src="{{asset('public/sms/plugins/morris/morris.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/sms/plugins/fastclick/
fastclick.min.js')}}"></script>
<script>
  let enrollments = <?php echo json_encode($enrollments) ?>;
  var result = $.parseJSON(enrollments);  
    
  $(function () {
    "use strict";

   
    //DONUT CHART
    var donut = new Morris.Donut({
      element: 'sales-chart',
      resize: true,
      colors: ["#3c8dbc", "#f56954", "#00a65a", '#a963a4'],
      data: [
        {label: "Total Students", value: {{$total_students}}},
        {label: "Total Teachers", value: {{$total_teachers}}},
        {label: "Total Subjects", value: {{$total_subjects}}},
        {label: "Total Courses", value: {{$total_courses}}} 
      ],
      hideHover: 'auto'
    });
    //BAR CHART
    var bar = new Morris.Bar({
      element: 'bar-chart',
      resize: true,
      data: result,
      barColors: ['#f56954'],
      xkey: 'y',
      ykeys: ['a'],
      labels: ['Enrolled Students'],
      hideHover: 'auto'
    });
  });
</script>
<!-- <script>
  $(function () {
    let enrollments = <?php echo json_encode($enrollments, JSON_PRETTY_PRINT) ?>;
    let session_name = enrollments.session_name;
    let enrolled_count = enrollments.enrolled_count;
    


    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var areaChart = new Chart(areaChartCanvas);

    var areaChartData = {
      labels: session_name,
      datasets: [    
        {
          label: "Enrolled Students",
          fillColor: "rgba(60,141,188,0.9)",
          pointColor: "#3b8bba",
          pointStrokeColor: "rgba(60,141,188,1)",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(60,141,188,1)",
          data: enrolled_count
        }
      ]
    };

    var areaChartOptions = {
      //Boolean - If we should show the scale at all
      showScale: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: false,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - Whether the line is curved between points
      bezierCurve: true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension: 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot: false,
      //Number - Radius of each point dot in pixels
      pointDotRadius: 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth: 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius: 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke: true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth: 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill: true,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true
    };

    //Create the line chart
    areaChart.Line(areaChartData, areaChartOptions);

    // Pie Chart
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var PieData = [
      {
        value: {{$total_students}},
        color: "#f56954",
        highlight: "#f56954",
        label: "Total Registered Students"
      },
      {
        value: {{$total_teachers}},
        color: "#00a65a",
        highlight: "#00a65a",
        label: "Total Registered Teachers"
      },
      {
        value: {{$total_subjects}},
        color: "#f39c12",
        highlight: "#f39c12",
        label: "Total Subjects"
      },
      {
        value: {{$total_courses}},
        color: "#00c0ef",
        highlight: "#00c0ef",
        label: "Total Courses Offered"
      },
     
    ];
    var pieOptions = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke: true,
      //String - The colour of each segment stroke
      segmentStrokeColor: "#fff",
      //Number - The width of each segment stroke
      segmentStrokeWidth: 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps: 100,
      //String - Animation easing effect
      animationEasing: "easeOutBounce",
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate: true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale: false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);
  });
</script> -->
@endsection