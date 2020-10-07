function updateCourseType()
{            
    $('#course_type_id').html('<option value="0">Loading....</option>');
    $.ajax({
        type : 'GET', 
        url: config.routes.updateCourseTypeRoute,
        data: {
            selected_course_type_id : $('#selected_course_type_id').val()
        }, 
        success:function(data){                    
            $('#course_type_id').html(data);                     
        }, 
        error: function( jqXHR, textStatus ) {
            toastr.error("Request Failed : " + textStatus);
        }     

    });
}
