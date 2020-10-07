<label>Academic Session: </label>
<select class="form-control select2" id="secondary_session_id" name="secondary_session_id">
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
    @if(!is_null($teacher_classes))
        @if(count($teacher_classes))
        <div class="header">Available Classes</div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="class-table">     
                <?php $i = 1; ?>
                <thead>
                    <tr style="background-color:#333; color:white;">
                        <th>SN</th>
                        <th>Subject</th>
                        <th>Room Code</th>                          
                        <th>Class Type</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>                                   
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teacher_classes as $index => $record)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$record->subject_name}}</td>
                        <td>{{$record->room_code}}</td>                 
                        <td>{{$record->type}}</td>
                        <td> {{ (new \App\DayAndDateTime)->returnDayName($record->day_id) }}
                        </td>
                        <td>{{ (new \App\DayAndDateTime)->parseTimein12HourFormat($record->start_time) }}</td>
                        <td>{{ (new \App\DayAndDateTime)->parseTimein12HourFormat($record->end_time) }}</td>
                        <td>                                            
                           <a href = "{{route('view-attendance', [$selected_session_id, $record->subject_id,$record->class_id])}}" data-lity><button data-toggle="tooltip" title="" class="btn btn-warning btn-flat" type="button" data-original-title="Update"> View Attendance<i class="fa fa-fw fa-edit"></i></button></a>          
                        </td>
                    </tr>
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
    <div class="alert alert-warning alert-dismissable">
       <h4><i class="icon fa fa-warning"></i>Please select session</h4>
    </div>
    @endif
</div>




