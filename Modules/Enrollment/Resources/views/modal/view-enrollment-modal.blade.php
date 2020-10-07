<div class="modal fade " id="viewEnrollment{{$record['enrollment_id']}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">

    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Enrollment Details</h4>
            </div>
            <div class="modal-body">                 
                <p class="text-center">
                    Student Name / ID: {{$record['student_name']}} /
                    {{$record['uniqueID']}}<br>
                    Session Name: {{$record['session_name']}}<br>
                    Enrolled For: {{$record['course_type']}} / {{$record['course_title']}}                                    
                    <table class="table table-bordered table-hover" id="myTable">        
                        <thead>
                            <tr style="background-color:#333; color:white;">
                                <td>Subject</td>
                                <td>Credit Points</td>
                                <td>Graded</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(json_decode($record['enrolled_subjects']) as $index => $subject)
                            <tr>                                
                                <td>{{$subject->subject_name}}</td>
                                <td>{{$subject->credit_points}}</td>
                                <td>{{$subject->is_graded}}</td>    
                                <td>
                                    <input type="hidden" value="{{$record['session_id']}}" id="sessionId">
                                    <input type="hidden" value="{{$record['enrollment_id']}}" id="enrollmentId">
                                    <input type="hidden" value="{{$record['student_id']}}" id="studentId">                                   
                                    <input type="hidden" class="subject_id" value="{{$subject->subject_id}}">
                                    @if($role != "student")
                                    <button data-toggle='tooltip' class='btn btn-danger remove-enrollment-btn{{$record["enrollment_id"]}}' type='button' data-original-title='Remove' > <i class='fa fa-fw fa-trash'></i></button></button>
                                    @else
                                    Not Available
                                    @endif
                                </td>                            
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </p>                 
            </div>
            <div class="modal-footer">
                <center>
                    
                </center>
            </div>
        </div> 
    </div>
</div>    
<script type="text/javascript">
$(document).ready(function() {    
    $(document).on('click', '.remove-enrollment-btn{{$record["enrollment_id"]}}', function(){              
        const sessionId = $(this).prev().prev().prev().prev().val();
        const enrollmentId = $(this).prev().prev().prev().val();
        const studentId = $(this).prev().prev().val();
        const subjectId = $(this).prev().val();
        const currentElement = $(this);        

        removeEnrolledSubjects(sessionId, enrollmentId, studentId, subjectId, currentElement);
        
        function removeEnrolledSubjects(sessionId, enrollmentId, studentId, subjectId, currentElement)
        {
            $.ajax({
                type: 'post', 
                url: '{{route("remove-enrolled-subjects-from-studentid-sessionid-enrollmentId-subjectId")}}', 
                data: {
                    session_id: sessionId, 
                    enrollment_id: enrollmentId, 
                    student_id: studentId, 
                    subject_id: subjectId 
                }, 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(response){
                    if(response == "success")
                    {
                        currentElement.parent().parent().remove();
                        toastr.success('Enrollment Removed Successfully');
                        $('#viewEnrollment{{$record["enrollment_id"]}}').on('hidden.bs.modal', function () 
                        {
                            location.reload();
                        });                        
                    }
                    else
                    {                        
                        toastr.error('Some errors occured during this operation. Please try again or contact for support.'); 
                        
                    }
                }

            });
        }
    });
});
</script>              


