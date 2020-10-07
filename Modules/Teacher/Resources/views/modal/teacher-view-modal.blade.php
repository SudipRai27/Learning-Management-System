<div class="modal fade" id="viewTeacher{{$teacher['id']}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Teacher Details</h4>
            </div>
            <div class="modal-body">                 
                <div class="profile-image">            
                    @if($teacher['photo'])
                    <img class="img-responsive" src = "{{url('/modules/teacher/resources/assets/images').'/'. $teacher['photo']}}" >
                    @else
                    <img class="img-responsive" src = "" >  
                @endif      
                </div>
                <div class="profile-detail">
                    <div class="main-head" style="text-align:center">               
                    </div>
                    <div class="second-head" style="text-align:center">
                        <span style="color:#333"><strong>Teacher ID: {{$teacher['teacher_id']}}</strong></span> 
                    </div>
                    <ul>
                        <li>
                            <label> Full Name: </label>
                            <span class="text-green"> {{$teacher['name']}}</span>
                        </li>
                        <li>
                            <label> Role: </label>
                            <span class="text-green"> 
                                @foreach(json_decode($teacher['role']) as $index => $d)
                                    {{$d->role_name}} 
                                @endforeach
                            </span>
                        </li>
                        
                        <li>
                            <label>Email:</label> 
                            <span class="text-green"> {{$teacher['email']}}</span>
                        </li>
                        <li>
                            <label>DOB:</label> 
                            <span class="text-green">{{$teacher['dob']}}</span>
                        </li>               
                        <li>
                            <label>Address:</label> 
                            <span class="text-green"> {{$teacher['address']}} </span>
                        </li>
                        <li>
                            <label>Phone Number:</label> 
                            <span class="text-green">  {{$teacher['phone']}}</span>
                        </li>
                        <li>
                            <label>Emergency Contact Name: </label> 
                            <span class="text-green"> {{$teacher['emergency_contact_name']}} </span>
                        </li>
                        <li>
                            <label>Emergency Contact Information: 
                            <span class="text-green"> {{$teacher['emergency_contact_number']}} </span>
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


