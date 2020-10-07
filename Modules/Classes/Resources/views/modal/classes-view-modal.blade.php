<div class="modal fade" id="viewClasses{{$record->id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Class Details</h4>
            </div>
            <div class="modal-body">   
                <div class="profile-detail">
                    <div class="main-head" style="text-align:center">               
                    </div>
                    <div class="second-head" style="text-align:center">
                        <span style="color:#333">Subject: {{$record->subject_name}}</span> 
                    </div>
                    <ul>
                        <li>
                            <label> In Room Code: </label><span class="text-green"> {{$record->room_code}}</span>
                        </li>
                        <li>
                            <label>Teacher:</label> <span class="text-green"> {{$record->teacher_name}}</span>
                        </li>
                        <li>
                            <label>Day:</label> <span class="text-green">{{ (new \App\DayAndDateTime)->returnDayName($record->day_id) }}</span>
                        </li>               
                        <li>
                            <label>Class Type:</label> <span class="text-green"> {{$record->type}} </span>
                        </li>
                        <li>
                            <label>Start Time:</label> <span class="text-green">  {{ (new \App\DayAndDateTime)->parseTimein12HourFormat($record->start_time) }}</span>
                        </li>
                        <li>
                            <label>End Time: </label> <span class="text-green"> {{ (new \App\DayAndDateTime)->parseTimein12HourFormat($record->end_time) }} </span>
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


