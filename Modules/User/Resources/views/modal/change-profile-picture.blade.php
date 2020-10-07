<div class="modal fade" id="uploadProilePicture{{$current_user->id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Change you profile picture here</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('change-profile-picture')}}" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" name="photo">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="user_id" value="{{$current_user->id}}">
                        <input type="hidden" name="role_name" value="{{$role}}">
                        <input type="submit" name="update" value="Change Profile Picture" class="btn btn-success btn-flat">                
                    </div>
                    {{csrf_field()}}
                </form>                
            </div>
            <div class="modal-footer">
                <center>                
                </center>
            </div>
        </div> 
    </div>
</div>					


