function updateTeacherByType(selectorId, type)
{
  
  $('#'+selectorId).select2({
  placeholder: 'Type Teacher ID or Name',
  ajax: {
    url: config.routes.getTeacherByTypeAutoCompleteRoute,
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
        type: type
      };
    },
    processResults: function (data) {
      return {
        results:  $.map(data, function (item) {
              return {
                  text: item.name +  ' - '  +item.teacher_id,
                  id: item.id
              }
          })
      };
    },
    cache: true
  }
  }); 
}