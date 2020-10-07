<div class="modal fade" id="editAssignedTutor{{$data->id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Edit Current Tutor</h4>
            </div>
            <form method="POST" action="{{route('update-assigned-teacher')}}" id="tutor-form{{$data->id}}">
            <div class="modal-body">      
                <label>Current Tutor:</label>              
                <input type="text" class="form-control" readonly value="{{$data->name}}">
                <input type="hidden" class="form-control" value="{{ $data->currentTeacherID}}" id="current_tutor_id{{$data->id}}">
                <label>New Tutor:</label>    
                <select class="form-control select2" name="teacher_id" id="tutor_id{{$data->id}}">
                    <option value="">Select</option>
                    @foreach($tutor as $key => $value)
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
        let uniqueId2 = '{{$data->id}}'; 
        let currentSelectorId2 = 'tutor_id';
        let selectorId2 = currentSelectorId2 + uniqueId2;
        //updateTeacherByType(selectorId2, 'tutor');

        $(document).on('click', "#submit-form-btn{{$data->id}}", function(e) {
            e.preventDefault();
            if($('#'+selectorId2).val() == null || !$('#'+selectorId2).val())
            {                
                toastr.error('Missing required info. Please provide the tutor');           
            }
            else
            {
                checkCurrentTutorClasses();                    
            }

            function checkCurrentTutorClasses()
            {
                const session_id = $('#session_id{{$data->id}}').val(); 
                const subject_id = $('#subject_id{{$data->id}}').val(); 
                const new_tutor_id = $('#tutor_id{{$data->id}}').val(); 
                let current_tutor_id = $('#current_tutor_id{{$data->id}}').val(); 
                let checkClasses = checkTeacherClasses(current_tutor_id, subject_id, session_id, 'lab');

                checkClasses.success(function(response){                                        
                    if(response == "no-classes")
                    {                             

                        checkIsTutorAlreadyAssigned(new_tutor_id, subject_id, session_id, 'lab');
                    }
                    else
                    {
                        let msg = 'Selected Tutor has lab classes assigned to this subject for this session. Please remove those classes and try again.';                        
                        toastr.error(msg);
                    }
                });
                checkClasses.error(function(jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                }); 
            }

            function checkIsTutorAlreadyAssigned(teacher_id, subject_id, session_id, type)
            {                            
                let result = checkAssignedTeacher(teacher_id, subject_id, session_id, 'lab');
                result.success(function(response){                    
                    if(response == "not-assigned")
                    {                                                
                        $('#tutor-form{{$data->id}}').submit();
                    }
                    else
                    {
                        let msg = 'Selected tutor is already assigned to this subject for this session';                        
                        toastr.error(msg);
                    }
                });
                result.error(function(jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                }); 
            }
        });

        $("#editAssignedTutor{{$data->id}}").on("hidden.bs.modal", function () {                   
            $('#tutor_id{{$data->id}}').val('').trigger('change');            
        });
                
    });

</script>


