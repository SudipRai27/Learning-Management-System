
@if(count($enrollment_subject_records))
    <?php  $i =1; ?>                        
<table class="table table-bordered table-hover" id="ajax-student-table">        
    <thead>
        <tr style="background-color:#333; color:white;">
        <th>SN</th>                                     
        <th>Session </th>
        <th>Enrolled Course Type</th>
        <th>Enrolled Course</th>
        <th>Enrolled Subjects</th>
        <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($enrollment_subject_records as $index => $record)                     
        <tr>
        <td>{{$i++}}</td>
        <td>{{$record['session_name']}}</td>     
        <td>{{$record['course_type']}}</td>     
        <td>{{$record['course_title']}}</td>            
        <td>           	
       	@foreach(json_decode($record['enrolled_subjects']) as $index => $subject)
            <li>{{$subject->subject_name}} </li>                         
        @endforeach
        </td>        

        <td>
            <button class="btn btn-primary btn-flat"  data-toggle="modal" data-target="#viewEnrollment{{$record['enrollment_id']}}" data-title="View Enrollment" data-message="View records">
            <i class="glyphicon glyphicon-file"></i>
            </button> 
            @include('enrollment::modal.view-enrollment-modal')   
            @if($role != "student")
            <a class="btn btn-danger btn-flat"  data-toggle="modal" data-target="#confirmDelete{{$record['enrollment_id']}}" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
            <i class="glyphicon glyphicon-trash"></i></a>            
            @include('enrollment::modal.enrollment-delete-modal')                       
            @endif                                                   
        </td>                
        </tr>
    @endforeach
    </tbody>                                    
</table>                    
@else
<div class="alert alert-warning alert-dismissable">
    <h4><i class="icon fa fa-warning"></i>NO ENROLLMENT RECORDS AVAILABLE</h4>
</div>  
@endif                      

