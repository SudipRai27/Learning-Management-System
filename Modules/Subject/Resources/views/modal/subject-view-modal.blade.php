<div class="modal fade" id="viewSubject{{$d->id}}" role="dialog" aria-labelledby="viewSubjectLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Subject Details </h4>
            </div>
            <div class="modal-body">    
                <div class="profile-detail">
                    <div class="main-head" style="text-align:center">               
                    </div>                     
                    <div class="second-head" style="text-align:center">
                        <span style="color:#333"><label>Subject Name:</label> {{ $d->subject_name }}</span> 
                    </div>
                    <ul>                        
                        <li>
                            <label>Credit Points:</label> <span class="text-green"> {{ $d->credit_points }}</span>
                        </li>
                        <li>
                            <label>Description:</label> <span class="text-green"> {{ $d->description }}</span>
                        </li>
                        <li>
                            <label>Graded:</label> <span class="text-green">{{$d->is_graded}}</span>
                        </li>               
                        <li>
                            <label>Course Type:</label> <span class="text-green"> {{$d->course_type}} </span>
                        </li>
                        <li>
                            <label>Course :</label> <span class="text-green"> {{$d->course_title}} </span>
                        </li>
                        <li>
                            <label>Full Marks:</label> <span class="text-green">  {{$d->full_marks}}</span>
                        </li>
                        <li>
                            <label>Pass Marks: </label> <span class="text-green"> {{$d->pass_marks}} </span>
                        </li>
                       
                    </ul>
                </div>                                                  
            </div>
            <div class="modal-footer">                
            </div>
        </div> 
    </div>
</div>					


