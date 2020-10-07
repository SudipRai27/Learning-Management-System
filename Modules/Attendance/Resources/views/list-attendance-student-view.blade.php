<label>Academic Session: </label>
<select class="form-control select2" id="student_session_id" name="student_session_id">
    <option value="">Select</option>
    @foreach($academic_session as $index => $d)
    <option value="{{$d->id}}"
        @if(isset($selected_session_id))
        @if($d->id == $selected_session_id)
        selected
        @endif
        @endif
        >{{$d->session_name}} {{$d->is_current == "yes" ? "-- Current Session --" : ''}}
    </option>                               
    @endforeach
</select>  
<div class="class-list">
@if(!is_null($student_classes))
    @if(count($student_classes))
    <div class="table-responsive">                                  
        <table class="table table-bordered table-hover" id="student-time-table">    
            <thead>
                <tr style="background-color:#333; color:white;">
                <th>SN</th>
                <th>Subject</th>
                <th>Room Code</th>
                <th>Teacher </th>                                   
                <th>Class Type</th>
                <th>Day</th>
                <th>Start Time</th>
                <th>End Time</th>       
                <th>Action</th>             
                </tr>
            </thead>
            <?php $i=1; ?>
            <tbody>            
                @foreach($student_classes as $index => $record)
                    @foreach($record as $index => $class)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{(new Modules\Subject\Entities\Subject)->getSubjectNameFromId($class->subject_id)}}</td>
                        <td>{{(new Modules\Room\Entities\Room)->getRoomNameFromId($class->room_id)}}</td>
                        <td>{{(new Modules\Teacher\Entities\Teacher)->getTeacherNameFromId($class->teacher_id)}}</td>
                        <td>{{$class->type}}</td>
                        <td>{{(new \App\DayAndDateTime)->returnDayName($class->day_id)}}</td>
                        <td>{{(new \App\DayAndDateTime)->parseTimein12HourFormat($class->start_time)}}</td>
                        <td>{{(new \App\DayAndDateTime)->parseTimein12HourFormat($class->end_time)}}</td>
                        <td>
                            <a href="{{route('view-attendance', [$selected_session_id, $class->subject_id,$class->id])}}" data-lity class="btn btn-primary btn-flat">View Attendance</a>            
                        </td>                           
                    </tr>
                    @endforeach
                @endforeach
            </tbody>                                    
        </table>
    </div>   
    @else
    <div class="alert alert-danger alert-dismissable">
        <h4><i class="icon fa fa-warning"></i>Classes not available.</h4>
    </div>
    @endif
@else
    <div class="alert alert-info alert-dismissable">
       <h4><i class="icon fa fa-warning"></i>Please select session</h4>
    </div>
@endif
</div>
   