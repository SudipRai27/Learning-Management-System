<div class="col-sm-6">
    <label>Course Type / Program Type: </label>
    <select class="form-control select2" id="course_type_id" name="course_type_id">
        <option value="0">Select</option>
        @foreach($course_type as $index => $d)
        <option value="{{$d->id}}"
        @if($d->id == $selected_course_type_id)
        selected
        @endif

            >{{$d->course_type}}</option>
        @endforeach
    </select>    
</div>
<div class="col-sm-6">
    <label>Program: </label>
    <select class="form-control select2" id="course_id" name="course_id">
        <option value="0">Please select course type first</option>
    </select>                    
</div>                                        