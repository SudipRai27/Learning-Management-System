function updateCourse(course_type_id)
{    
    $('#course_id').html('<option value="0">Loading....</option>');
    $.ajax({
        type: 'GET', 
        url: config.routes.updateCourseRoute,
        data: {
            course_type_id,
            selected_course_id: $('#selected_course_id').val()
        }, 
        success:function(data)
        {
            $('#course_id').html(data);
        }, 
        error: function( jqXHR, textStatus ) {
            toastr.error("Request Failed : " + textStatus);
        }     
    });
}
