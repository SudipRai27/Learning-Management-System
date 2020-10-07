if($('#role').val() == "teacher")
{       
    const updateTeacherAssignedSubjectList = (session_id, teacher_id) => {                
        $('#subject_id').html('<option>Loading</option>')
        $.ajax({
            type : 'GET', 
            url: config.routes.getAssignedTeacherSubjectsRoute, 
            data: {
                session_id, teacher_id, 
                selected_subject_id: $('#selected_subject_id').val()
            }, 
            success: function(data) {                        
                $('#subject_id').html(data);
            }, 
            error: function(jqXHR)
            {                   
                toastr.error(jqXHR.statusText + " " + jqXHR.status);
            }
        });
    }


    if($('#session_id').val())
    {       
        updateTeacherAssignedSubjectList($('#session_id').val(), $('#teacher_id').val());
    }

    $('#session_id').on('change', function () {     
        updateTeacherAssignedSubjectList($(this).val(), $('#teacher_id').val());
    })      

}
