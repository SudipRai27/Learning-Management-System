<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">            
        @if($current_user->photo)        
        <img src="{{url('/modules/teacher/resources/assets/images').'/'. $current_user->photo}}" class="img-circle" width="20" height="20">       
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
          <i class="fa fa-file" aria-hidden="true"></i>
          <span>Lecture Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-lecture')}}"><i class="fa fa-circle-o"></i>List Lecture</a></li>                          
          <li class="treeview-item"><a href="{{route('create-lecture')}}"><i class="fa fa-circle-o"></i>Create Lecture</a></li>                      
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
          <li class="treeview-item"><a href="{{route('create-attendance')}}"><i class="fa fa-circle-o"></i>Create / Update Attendance</a></li>                      
        </ul>
      </li> 

      <li class="treeview">
        <a href="#">
          <i class="fa fa-file-word-o" aria-hidden="true"></i>
          <span>Assignment Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-assignment')}}"><i class="fa fa-circle-o"></i>List Assignment</a></li>                          
          <li class="treeview-item"><a href="{{route('create-assignment')}}"><i class="fa fa-circle-o"></i>Create Assignment</a></li>                      
          <li class="treeview-item"><a href="{{route('list-assignment-marks')}}"><i class="fa fa-circle-o"></i>List Assignment Marks</a></li>    
          <li class="treeview-item"><a href="{{route('view-assignment-submission-backend')}}"><i class="fa fa-circle-o"></i>View Submissions</a></li>                           
          <li class="treeview-item"><a href="{{route('upload-assignment-marks')}}"><i class="fa fa-circle-o"></i>Upload Assignment Marks</a></li>                      
        </ul>
      </li> 


      <li class="treeview">
        <a href="#">
          <i class="fa fa-tasks"></i>
          <span>Exam Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-exam')}}"><i class="fa fa-circle-o"></i>List Exam</a></li>                          
          <li class="treeview-item"><a href="{{route('create-exam')}}"><i class="fa fa-circle-o"></i>Create Exam</a></li>                      
          <li class="treeview-item"><a href="{{route('list-exam-marks')}}"><i class="fa fa-circle-o"></i>List Exam Marks</a></li>                      
          <li class="treeview-item"><a href="{{route('upload-exam-marks')}}"><i class="fa fa-circle-o"></i>Upload Exam Marks</a></li>                      
        </ul>
      </li> 


    </ul>

  </section>
  <!-- /.sidebar -->
</aside>