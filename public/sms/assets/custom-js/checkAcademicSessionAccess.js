function checkAccessForSession(session_id, access_type)
{       
    return $.ajax({
        url: config.routes.checkAcademicSessionSetting, 
        data: {
            
            session_id: session_id, 
            access_type: access_type
        },
        type: 'GET', 
        async: !1,                
        error:function(jqXHR, textStatus ) {
            toastr.error("Request Failed : " + textStatus);
        }  
    });   
}