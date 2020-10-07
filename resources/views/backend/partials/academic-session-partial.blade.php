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
        >{{$d->session_name}} {{ $d->is_current == "yes" ? '-- Current Session --' : '' }}
    </option>                               
    @endforeach
</select>    
