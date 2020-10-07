function getStudentAutocomplete()
{
	$('#student_id').select2({
        placeholder: 'Type the name of student to see results',
        ajax: {
          url: config.routes.updateStudentAutoCompleteRoute,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.name +  ' - '  +item.student_id,
                        id: item.id
                    }
                })
            };
          },
          cache: true
        }
    }); 
}