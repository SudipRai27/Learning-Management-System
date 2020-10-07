<div class="modal fade text-danger" id="deleteResult{{$record->student_id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-danger">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">DELETE CONFIRMATION</h4>
            </div>
            <div class="modal-body">                 
                 <p class="text-center">Are You Sure Want To Delete the result for {{$record->name}} ? Deleting this will delete all child records/</p>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>  
                    <form method="post" action="{{route('delete-result')}}" style="display:inline">
                        <input type="hidden" name="session_id" value="{{$selected_session_id}}">
                        <input type="hidden" name="student_id" value="{{$record->student_id}}">
                        <input type="submit" name="Delete" class="btn btn-danger" value="Delete">
                        {{csrf_field()}}
                    </form>           
                    <!--  -->
                </center>
            </div>
        </div> 
    </div>
</div>                  


