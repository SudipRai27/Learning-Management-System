<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">            
        @if($current_user->photo)
        <img src="{{url('/modules/admin/resources/assets/images').'/'. $current_user->photo}}" class="img-circle" width="20" height="20">             
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
          <i class="fa fa-industry" aria-hidden="true"></i>
          <span>Academics Manager</span>         
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-academic-session')}}"><i class="fa fa-circle-o"></i>List Academic Session</a></li>          
          <li class="treeview-item"><a href="{{route('create-academic-session')}}"><i class="fa fa-circle-o"></i>Create Academic Session</a></li>          
        </ul>
      </li> 
      <li class="treeview">
        <a href="#">
          <i class="fa fa-clone" aria-hidden="true"></i>
          <span>Courses Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-course-type')}}"><i class="fa fa-circle-o"></i>List Course Type</a></li>          
          <li class="treeview-item"><a href="{{route('create-course-type')}}"><i class="fa fa-circle-o"></i>Create Course Type</a></li>          
          <li class="treeview-item"><a href="{{route('list-course')}}"><i class="fa fa-circle-o"></i>List Course </a></li>          
          <li class="treeview-item"><a href="{{route('create-course')}}"><i class="fa fa-circle-o"></i>Create Course </a></li>          
        </ul>
      </li> 
      <li class="treeview">
        <a href="#">
          <i class="fa fa-graduation-cap" aria-hidden="true"></i>
          <span>Subject/Unit Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-subject')}}"><i class="fa fa-circle-o"></i>List Subject</a></li>          
          <li class="treeview-item"><a href="{{route('create-subject')}}"><i class="fa fa-circle-o"></i>Create Subject</a></li>          
                  
        </ul>
      </li> 
      <li class="treeview">
        <a href="#">
          <i class="fa fa-user-circle" aria-hidden="true"></i>
          <span>Student Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-student')}}"><i class="fa fa-circle-o"></i>List Students</a></li>          
          <li class="treeview-item"><a href="{{route('create-student')}}"><i class="fa fa-circle-o"></i>Create Students</a></li>          
                  
        </ul>
      </li> 
      <li class="treeview">
        <a href="#">
          <i class="fa fa-user-circle-o" aria-hidden="true"></i>
          <span>Teacher Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-teacher')}}"><i class="fa fa-circle-o"></i>List Teacher</a></li>          
          <li class="treeview-item"><a href="{{route('create-teacher')}}"><i class="fa fa-circle-o"></i>Create Teacher</a></li>          
          <li class="treeview-item"><a href="{{route('list-assigned-teacher')}}"><i class="fa fa-circle-o"></i>List Assigned Teacher</a></li>          
          <li class="treeview-item"><a href="{{route('assign-teacher')}}"><i class="fa fa-circle-o"></i>Assign Teacher</a></li>          
                  
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
          <li class="treeview-item"><a href="{{route('create-enrollment')}}"><i class="fa fa-circle-o"></i>Enroll Students</a></li>            
        </ul>
      </li> 
    
      <li class="treeview">
        <a href="#">
          <i class="fa fa-plus" aria-hidden="true"></i>
          <span>Room Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-room')}}"><i class="fa fa-circle-o"></i>List Room</a></li>           
        </ul>
      </li> 

    <li class="treeview">
        <a href="#">
          <i class="fa fa-random" aria-hidden="true"></i>
          <span>Class Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-class')}}"><i class="fa fa-circle-o"></i>List Class</a></li>                          
                  
        </ul>
      </li> 

      <li class="treeview">
        <a href="#">
          <i class="fa fa-sliders" aria-hidden="true"></i>
          <span>Student Timetable</span>
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
          <li class="treeview-item"><a href="{{route('create-attendance')}}"><i class="fa fa-circle-o"></i>Create / Update Attendance</a></li>                      
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

       <li class="treeview">
        <a href="#">
          <i class="fa fa-free-code-camp" aria-hidden="true"></i>
          <span>Result Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-result')}}"><i class="fa fa-circle-o"></i>List Result</a></li>                          
          <li class="treeview-item"><a href="{{route('generate-result')}}"><i class="fa fa-circle-o"></i>Generate Result</a></li>                      
        </ul>
      </li> 


       <li class="treeview">
        <a href="#">
          <i class="fa fa-suitcase"></i>
          <span>Event Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-event')}}"><i class="fa fa-circle-o"></i>List Event</a></li>                          
          <li class="treeview-item"><a href="{{route('create-event')}}"><i class="fa fa-circle-o"></i>Create Event</a></li>                      
        </ul>
      </li> 


      <li class="treeview">
        <a href="#">
          <i class="fa fa-ellipsis-h"></i>
          <span>Slider Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-slider')}}"><i class="fa fa-circle-o"></i>List Slider</a></li>                          
          <li class="treeview-item"><a href="{{route('create-slider')}}"><i class="fa fa-circle-o"></i>Create / Slider</a></li>                      
        </ul>
      </li> 

       <li class="treeview">
        <a href="#">
          <i class="fa fa-user-plus"></i>
          <span>Admin Manager</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-admin')}}"><i class="fa fa-circle-o"></i>List Admin</a></li>                          
          <li class="treeview-item"><a href="{{route('create-admin')}}"><i class="fa fa-circle-o"></i>Create Admin</a></li>                      
        </ul>
      </li> 


       <li class="treeview">
        <a href="#">
          <i class="fa fa-universal-access"></i>
          <span>Access Control</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="{{route('list-modules')}}"><i class="fa fa-circle-o"></i>Set Permission / Access Control </a></li>              
        </ul>
      </li> 
      @if(Route::current()->getName() == 'user-profile')
        <li class="treeview">
        <a href="#">
          <i class="fa fa-globe"></i>
          <span>test</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
                            
        </ul>
      </li> 

      <li class="treeview">
        <a href="#">
          <i class="fa fa-globe"></i>
          <span>test</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li class="treeview-item"><a href="#"><i class="fa fa-circle-o"></i>-- </a></li>                                       
        </ul>
      </li>
      @endif
      
    </ul>

  </section>
  <!-- /.sidebar -->
</aside>