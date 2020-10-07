function updateStudentList(session_id)
{	
	$('#student_id').html('<option value="0">Loading....</option>');
	$.ajax({
		url: config.routes.getEnrolledStudentListRoute, 
		type: 'GET',
		data: {
			session_id, 
			selected_student_id: $('#selected_student_id').val()
		},
		success:function(response) {    				
			$('#student_id').html(response);
		}, 
		error: function(jqXHR) {
			toastr.error(jqXHR.status + " " + jqXHR.statusText);

		}
	})
}