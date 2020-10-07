<div class="modal fade text-danger" id="deleteAssignedTeacher{{$data->id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-danger">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">DELETE CONFIRMATION</h4>
            </div>
            <div class="modal-body">                 
                 <p class="text-center">Are You Sure Want To Delete the Assigned Teacher Permanently? </p>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-danger btn-flat" id="remove-assigned-teacher-ajax-btn{{$data->id}}" data-dismiss="modal">Remove</button>          

                    <input type="text" value="{{$data->id}}" id="assignedId{{$data->id}}">                        
                    </form>                    
                </center>
            </div>
        </div> 
    </div>
</div>					
<script type="text/javascript">
    $(document).on('click', '#remove-assigned-teacher-ajax-btn{{$data->id}}', function() {
        const assignedId = $('#assignedId{{$data->id}}').val(); 
        $('#deleteAssignedTeacher{{$data->id}}').parent().parent().remove();
        $('#deleteAssignedTeacher{{$data->id}}').remove();
        $('#deleteAssignedTeacher{{$data->id}}').data('modal', null);
        $('.modal-backdrop').remove();
        //deleteAssignedTeacherViaAjax(assignedId);
        
    });


    function deleteAssignedTeacherViaAjax(assignedId)
    {
        $.ajax({
            type: 'POST', 
            url: '{{route("ajax-delete-assigned-teacher")}}', 
            data: {
                assignedId
            }, 
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },     
            success:function (data) {
                console.log(data);
                if(data.status == "success")
                {
                    
                    
                    
                    toastr.success(data.msg);
                }
                else
                {
                    toastr.error(data.msg);
                }
            }, 
            error: function( jqXHR, textStatus ) {
                toastr.error("Request Failed : " + textStatus);
            }     
        });
    }
</script>

