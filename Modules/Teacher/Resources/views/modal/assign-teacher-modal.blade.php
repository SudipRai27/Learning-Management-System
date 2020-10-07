<div class="modal fade" id="assignTeacher{{$subject->id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Assign Teacher</h4>
                
            </div>
            <div class="modal-body">     
                <p style="text-align: center;">Subject Name: {{$subject->subject_name}}</p>
                <p style="text-align: center;">Course Type:{{$subject->course_type}}</p>
                <p style="text-align: center;">Course: {{$subject->course_title}}</p>
                <table class="table table-bordered table-hover" id="myTable">        
                        <thead>
                            <tr style="background-color:#333; color:white;">
                                <td>Lecture</td>                                
                                <td>Lab</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <label>This select box will only load lecturers: </label>
                                    <select class="form-control select2" name="lecturer_id" id="lecturer_id{{$subject->id}}">
                                        <option value="">Select</option>
                                        @foreach($lecturer as $index => $value)
                                        <option value="{{$value->id}}">{{$value->name}} -- {{$value->teacher_id}}</option>
                                        @endforeach
                                    </select>
                                    <p id="lecturer-msg{{$subject->id}}"></p>                                                                      
                                    <button id="lecturer-remove-btn{{$subject->id}}" class="btn btn-danger btn-flat">Clear lecturer select box</button>
                                </td>
                                <td>
                                    <label>This select box will load tutors only: </label>
                                    <select class="form-control select2" id="tutor_id{{$subject->id}}" name="tutor_id">
                                        <option value="">Select</option>
                                        @foreach($tutor as $key => $value)
                                        <option value="{{$value->id}}">{{$value->name}} -- {{$value->teacher_id}}</option>
                                        @endforeach
                                    </select>
                                    <p id="tutor-msg{{$subject->id}}">
                                    </p>
                                                                        
                                    <button id="tutor-remove-btn{{$subject->id}}" class="btn btn-danger btn-flat">Clear tutor select box</button>
                                </td>
                            </tr>
                        </tbody>
                </table>            
                <table class="table table-bordered table-hover" id="subject-enrollment-table">                             
                </table>
            </div>
            <div class="modal-footer"> 
                <input type="hidden" value="{{$subject->id}}" id="subject_id{{$subject->id}}">
                <input type="submit" value="Assign" class="btn btn-success btn-flat form-control" id="assign-teacher-button{{$subject->id}}">             
            </div>
        </div> 
    </div>
</div>		
<script type="text/javascript">

    $(document).ready(function () {      

        let uniqueId = '{{$subject->id}}'; 
        let currentSelectorId = 'lecturer_id';
        let selectorId = currentSelectorId + uniqueId;
        //updateTeacherByType(selectorId, 'lecturer');


        let uniqueId2 = '{{$subject->id}}'; 
        let currentSelectorId2 = 'tutor_id';
        let selectorId2 = currentSelectorId2 + uniqueId2;
        //updateTeacherByType(selectorId2, 'tutor');
       

        $(document).on('click','#assign-teacher-button{{$subject->id}}', function() {       
            const lecturer_id = $('#lecturer_id{{$subject->id}}').val();
            const tutor_id = $('#tutor_id{{$subject->id}}').val();
            const subject_id = $('#subject_id{{$subject->id}}').val();
            const session_id = $('#session_id').val();
            
            if(!lecturer_id && !tutor_id)
            {
                toastr.error('Please provide at least a lecturer or a tutor');
            }
            else
            {     
                processAssignTeacher(lecturer_id, tutor_id, subject_id, session_id);
            }

        });


        function processAssignTeacher(lecturer_id,tutor_id, subject_id, session_id)
        {
            let ajax_error = 'Some errors occured while processing this request. Please try again later';
            if(lecturer_id)
            {                                    
                let result = checkAssignedTeacher(lecturer_id, subject_id, session_id, 'lecture');
                result.success(function(response){                    
                    if(response == "not-assigned")
                    {
                        assignTeacher(lecturer_id, subject_id, session_id, 'lecture');
                    }
                    else
                    {
                        let msg = 'Selected Lecturer is already assigned to this subject for this session';
                        $('#lecturer-msg{{$subject->id}}').text(msg).css({"color": "red"});
                        toastr.error(msg);
                    }
                });
                result.error(function(jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                });
            }

            if(tutor_id)
            {                                    
                let result = checkAssignedTeacher(tutor_id, subject_id, session_id, 'lab');
                result.success(function(response){                    
                    if(response == "not-assigned")
                    {
                        assignTeacher(tutor_id, subject_id, session_id, 'lab');
                    }
                    else
                    {
                        let msg = 'Selected Tutor is already assigned to this subject for this session';
                        $('#tutor-msg{{$subject->id}}').text(msg).css({"color": "red"});
                        toastr.error(msg);
                    }
                });
                result.error(function(jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                });
            }
        }    

        function assignTeacher(teacher_id, subject_id, session_id, type)
        {
            $.ajax({
                type: 'POST', 
                url : '{{route("assign-teacher-ajax-post")}}', 
                data: {
                    teacher_id,                     
                    subject_id, 
                    session_id, 
                    type
                }, 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },                
                success: function(response){                    
                    if(response.msg == "success")
                    {
                        toastr.success('Assigned Successfully');
                        if(response.type == "lecture")
                        {
                            $('#lecturer-msg{{$subject->id}}').text('Selected Lecture is  successfully assigned to this subject for this session').css({"color": "green"});   
                        }
                        if(response.type == "lab")
                        {
                            $('#tutor-msg{{$subject->id}}').text('Selected Tutor is successfully assigned to this subject for this session').css({"color": "green"});                            
                        }
                    }
                    else
                    {
                        toastr.error('There were some problems assigning the lecturer or tutor. Please contact administrator');
                    }
                }, 
                error: function( jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                }     

            });
        }

        $(document).on('click','#lecturer-remove-btn{{$subject->id}}', function() {   
            $('#lecturer_id{{$subject->id}}').val('').trigger('change');
            $('#lecturer-msg{{$subject->id}}').text('');
            
        });    

        $(document).on('click','#tutor-remove-btn{{$subject->id}}', function() {   
            $('#tutor_id{{$subject->id}}').val('').trigger('change'); 
            $('#tutor-msg{{$subject->id}}').text('');
        });    

        $("#assignTeacher{{$subject->id}}").on("hidden.bs.modal", function () {            
            $('#lecturer_id{{$subject->id}}').val('').trigger('change');
            $('#tutor_id{{$subject->id}}').val('').trigger('change');
            $('#lecturer-msg{{$subject->id}}').text('');           
            $('#tutor-msg{{$subject->id}}').text('');
        });
    });  
</script>
