<div class="col-sm-3 select-div">
    <label>Academic Session: </label>
    <select class="form-control select2" id="session_id" name="session_id">
        <option value="0">Select</option>
        @foreach($academic_session as $index => $d)
        <option value="{{$d->id}}"
            @if(isset($selected_session_id))
            @if($d->id == $selected_session_id)
            selected
            @endif
            @endif
            >{{$d->session_name}}
        </option>                               
        @endforeach
    </select>    
</div>    
<div class="col-sm-3 select-div">
    <label>Course Type / Program Type: </label>
    <select class="form-control select2" id="course_type_id" name="course_type_id">
        <option value="0">Please select academic session first</option>
    </select>
</div>
<div class="col-sm-3 select-div">
    <label>Program: </label>
    <select class="form-control select2" id="course_id" name="course_id">
        <option value="0">Please select course type first</option>
    </select>                    
</div>                    