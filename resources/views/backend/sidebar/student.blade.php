<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">            
        @if($current_user->photo)
        <img src="{{url('/modules/student/resources/assets/images').'/'. $current_user->photo}}" class="img-circle" width="20" height="20">             
        @else
        <img src="{{ URL::to('public/images/default-user.png')}}" class="img-circle" width="20" height="20">     
        @endif
       </div>
      <div class="pull-left info">
        <p>{{ $current_user->name }}</p>

        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu scrollbar-dynamic">
      <li class="header">MAIN NAVIGATION</li>
      <li class="treeview">
        <a href="{{URL::route('user-home')}}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>


      <li class="treeview">
        <a href="#">
          <i class="fa fa-graduation-cap" aria-hidden="true"></i>
          <span>My Subjects</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('view-teacher-student-subjects')}}"><i class="fa fa-circle-o"></i>View Subjects</a></li>                          
                             
        </ul>
      </li> 

      <li class="treeview">
        <a href="#">
          <i class="fa fa-area-chart" aria-hidden="true"></i>
          <span>Enrollment Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-enrollment')}}"><i class="fa fa-circle-o"></i>List Enrollment</a></li>          
          <li class="treeview-item"><a href="{{route('create-enrollment')}}"><i class="fa fa-circle-o"></i>Enroll</a></li>            
        </ul>
      </li> 

      <li class="treeview">
        <a href="#">
          <i class="fa fa-sliders" aria-hidden="true"></i>
          <span> Timetable</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-timetable')}}"><i class="fa fa-circle-o"></i>List TimeTable</a></li>                          
          <li class="treeview-item"><a href="{{route('create-timetable')}}"><i class="fa fa-circle-o"></i>Create / Update TimeTable</a></li>                      
        </ul>
      </li> 

      <li class="treeview">
        <a href="#">
          <i class="fa fa-hand-spock-o" aria-hidden="true"></i>
          <span>Attendance Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-attendance')}}"><i class="fa fa-circle-o"></i>List Attendance</a></li>                          
                             
        </ul>
      </li> 

      <li class="treeview">
        <a href="#">
          <i class="fa fa-suitcase"></i>
          <span>Grades Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('view-grades')}}"><i class="fa fa-circle-o"></i>View grade</a></li>                          
                             
        </ul>
      </li> 


    </ul>

  </section>
  <!-- /.sidebar -->
</aside>