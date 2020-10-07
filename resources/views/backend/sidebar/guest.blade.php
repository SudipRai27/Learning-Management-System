<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">            
        @if($current_user->photo)
        <img src="{{ URL::to('public/images/user_photos/',$current_user->photo)}}" class="img-circle" width="20" height="20">     
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
      
      

    </ul>

  </section>
  <!-- /.sidebar -->
</aside>