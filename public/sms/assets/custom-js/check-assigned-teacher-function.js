function checkAssignedTeacher(teacher_id, subject_id, session_id, type)
{        
    return $.ajax({
        url: config.routes.checkAssignedTeacherRoute,
        type: 'GET', 
        data: {
            teacher_id, 
            subject_id, 
            session_id, 
            type
        }                            
    });
}  