function updateRoomListByTypeClassType(type)
{
	$('#room_id').html("<option value=''>Loading</option>");
	let room_type = '';
	if(type == "lecture")
	{
		room_type = 'lecture_room';
	}
	else
	{
		room_type = 'lab_room';
	}
	$.ajax({
		url : config.routes.getRoomByRoomTypeRoute,
		type: 'GET', 
		data: {
			room_type, 
			selected_room_id: $('#old_room_id').val()
		}, 
		success:function (data) {
			console.log(data);
			$('#room_id').html(data);
		}, 	
		error: function (jqXhr, textstatus) {
			toastr.error(textstatus);
		}
	})
}
