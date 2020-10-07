<div class="modal fade" id="viewStudent{{$student->id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Student Details</h4>
            </div>
            <div class="modal-body">   
                <div class="profile-image">         
                    @if($student->photo)
                    <img class="img-responsive" src = "{{url('/modules/student/resources/assets/images').'/'. $student->photo}}" >
                    @else
                    <img class="img-responsive" src = "" >  
                    @endif      
                </div>
                <div class="profile-detail">
                    <div class="main-head" style="text-align:center">               
                    </div>
                    <div class="second-head" style="text-align:center">
                        <span style="color:#333">Student ID: {{$student->student_id}}</span> 
                    </div>
                    <ul>
                        <li>
                            <label> Full Name: </label><span class="text-green"> {{$student->name}}</span>
                        </li>
                        <li>
                            <label>Email:</label> <span class="text-green"> {{$student->email}}</span>
                        </li>
                        <li>
                            <label>DOB:</label> <span class="text-green">{{$student->dob}}</span>
                        </li>               
                        <li>
                            <label>Address:</label> <span class="text-green"> {{$student->address}} </span>
                        </li>
                        <li>
                            <label>Phone Number:</label> <span class="text-green">  {{$student->phone}}</span>
                        </li>
                        <li>
                            <label>Emergency Contact Name: </label> <span class="text-green"> {{$student->emergency_contact_name}} </span>
                        </li>
                        <li>
                            <label>Emergency Contact Information: <span class="text-green"> {{$student->emergency_contact_number}} </span>
                        </li>                                   
                        <li>
                            <label>Current Program Type / Course Type: <span class="text-green"> {{ Modules\Course\Entities\CourseType::where('id', $student->current_course_type_id)->pluck('course_type')[0]}} </span>
                        </li>               
                        <li>
                            <label>Current Course: <span class="text-green"> {{ Modules\Course\Entities\Course::where('id', $student->current_course_id)->pluck('course_title')[0]}}  </span>
                        </li>               
                    </ul>
                </div>                    
            </div>
            <div class="modal-footer">
                <center>                
                </center>
            </div>
        </div> 
    </div>
</div>					


