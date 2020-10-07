<div class="modal fade" id="editAssignedLecturer{{$data->id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Edit Current Lecturer</h4>
            </div>
            <form method="POST" action="{{route('update-assigned-teacher')}}" id="lecturer-form{{$data->id}}">
            <div class="modal-body">      
                <label>Current Lecturer:</label>              
                <input type="text" class="form-control" readonly value="{{$data->name}}">
                <input type="hidden" class="form-control" value="{{ $data->currentTeacherID}}" id="current_lecturer_id{{$data->id}}">
                <label>New Lecturer:</label>    
                <select class="form-control select2" name="teacher_id" id="lecturer_id{{$data->id}}">
                    <option value="">Select</option>
                    @foreach($lecturer as $index => $value)
                    <option value="{{$value->id}}">{{$value->name}} -- {{$value->teacher_id}}</option>
                    @endforeach
                </select> 
                <input type="hidden" value="{{$data->id}}" name="assignedId" id="assignedId{{$data->id}}">              
            </div>
            <div class="modal-footer">  
                <center>                                                                   
                        <input type="submit" class="btn btn-success btn-flat" value="Update" id="submit-form-btn{{$data->id}}">
                </center>
            </div>
            {{csrf_field()}}
            </form>
            <input type="hidden" value="{{$data->session_id}}" name="session_id" id="session_id{{$data->id}}">
            <input type="hidden" value="{{$data->subject_id}}" name="subject_id" id="subject_id{{$data->id}}">
        </div> 
    </div>
</div>					
<script type="text/javascript">    

    $(document).ready(function() {
        
        let uniqueId = '{{$data->id}}'; 
        let currentSelectorId = 'lecturer_id';
        let selectorId = currentSelectorId + uniqueId;
        // Old proces
        // updateTeacherByType(selectorId, 'lecturer');

        $(document).on('click', "#submit-form-btn{{$data->id}}", function(e) {
            e.preventDefault();
            if($('#'+selectorId).val() == null || !$('#'+selectorId).val())
            {         
                toastr.error('Missing required info. Please provide the lecturer');
            }
            else
            {
                checkCurrentLecturerClasses();
            }

            function checkCurrentLecturerClasses()
            {
                const session_id = $('#session_id{{$data->id}}').val(); 
                const subject_id = $('#subject_id{{$data->id}}').val(); 
                const new_lecturer_id = $('#lecturer_id{{$data->id}}').val(); 
                let current_lecturer_id = $('#current_lecturer_id{{$data->id}}').val(); 
                let checkClasses = checkTeacherClasses(current_lecturer_id, subject_id, session_id, 'lecture');
                checkClasses.success(function(response){                    
                    if(response == "no-classes")
                    {                                                
                        checkIsLecturerAlreadyAssigned(new_lecturer_id, subject_id, session_id, 'lecture');
                    }
                    else
                    {
                        let msg = 'The lecturer that you are trying to update has lecture classes assigned to this subject for this session. Please remove those classes and try again.';                        
                        toastr.error(msg);
                    }
                });
                checkClasses.error(function(jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                }); 
            }

            function checkIsLecturerAlreadyAssigned(teacher_id, subject_id, session_id, type)
            {
                let result = checkAssignedTeacher(teacher_id, subject_id, session_id, type);
                result.success(function(response){                    
                    if(response == "not-assigned")
                    {                                                
                        $('#lecturer-form{{$data->id}}').submit();
                    }
                    else
                    {
                        let msg = 'Selected Lecturer is already assigned to this subject for this session';                        
                        toastr.error(msg);
                    }
                });
                result.error(function(jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                });  
            }
        });

        $("#editAssignedLecturer{{$data->id}}").on("hidden.bs.modal", function () {                
            $('#lecturer_id{{$data->id}}').val('').trigger('change');            
        });
                
    });

</script>


