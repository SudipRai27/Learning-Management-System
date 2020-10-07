function updateTeacherListByClassType(session_id, subject_id, type)
{
	$('#teacher_id').html("<option value=''>Loading</option>");
	$.ajax({
		url : config.routes.getAssignedTeacherByClassType,
		type: 'GET', 
		data: {
			type,
			selected_teacher_id: $('#old_teacher_id').val(), 
			session_id, 
			subject_id

		}, 
		success:function (data) {
			$('#teacher_id').html(data);
		}, 	
		error: function (jqXhr, textstatus) {
			toastr.error(textstatus);
		}
	});
}
