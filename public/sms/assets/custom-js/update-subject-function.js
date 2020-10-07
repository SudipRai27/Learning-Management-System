function updateSubject(course_type_id, course_id)
{
	$('#subject_id').html('<option value="0">Loading....</option>');
    $.ajax({
        type : 'GET', 
        url: config.routes.updateSubjectRoute,
        data: {
        	course_type_id,
        	course_id, 
            selected_subject_id : $('#selected_subject_id').val()
        }, 
        success:function(data){                    
            $('#subject_id').html(data);                          
        }, 
        error: function( jqXHR, textStatus ) {
            toastr.error("Request Failed : " + textStatus);
        }     

    });
}