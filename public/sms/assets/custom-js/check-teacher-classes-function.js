function checkTeacherClasses(teacher_id, subject_id, session_id, type)
{        
    return $.ajax({
        url: config.routes.checkTeacherClasses,
        type: 'GET', 
        data: {
            teacher_id, 
            subject_id, 
            session_id, 
            type
        }                            
    });
}  