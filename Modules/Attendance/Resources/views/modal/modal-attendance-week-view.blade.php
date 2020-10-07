<div class="modal fade" id="updateAttendance{{$record->class_id}}" role="dialog" aria-labelledby="viewSubjectLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Select Week </h4>
            </div>
            <div class="modal-body">    
                <div class="profile-detail">                    
                    <div class="second-head" style="text-align:center">
                    <table class="table table-bordered table-hover" id="myTable">        
                        <thead>
                            <tr style="background-color:#333; color:white;">
                                <td>SN</td>
                                <td>Week</td>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php  $i = 1; ?>
                            @foreach($attendance_weeks as $key => $week)
                            <tr>                                
                                <td>{{$i++}}</td>
                                @if($role == "teacher")
                                <td><a href="{{route('update-attendance', [$selected_session_id, $record->subject_id,$record->class_id, $key])}}" data-lity>{{$week}}</a></td>     
                                @else
                                <td><a href="{{route('update-attendance', [$selected_session_id, $selected_subject_id,$record->class_id, $key])}}" data-lity>{{$week}}</a></td>                                
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>                                               
            </div>
            <div class="modal-footer">                
            </div>
        </div> 
    </div>
</div>					


