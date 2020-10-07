<div class="modal fade text-danger" id="deleteAssignedTeacher{{$data->id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-danger">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">DELETE CONFIRMATION</h4>
            </div>
            <div class="modal-body">                 
                 <p class="text-center">Are You Sure Want To Delete the Assigned Teacher ? </p>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>             
                    <form method="post" action="{{route('delete-assigned-teacher', $data->id)}}" style="display:inline">                        
                        <input type="submit" name="Delete" class="btn btn-danger" value="Delete">
                        {{csrf_field()}}
                    </form>                    
                </center>
            </div>
        </div> 
    </div>
</div>					


