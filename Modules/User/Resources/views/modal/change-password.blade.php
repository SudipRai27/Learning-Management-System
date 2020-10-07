<div class="modal fade" id="changePassword{{$current_user->id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Change you password here</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('change-password')}}" method="POST" id="change-password-form">
                    <input type="password" name="password" id="password" placeholder="Enter new password" class="form-control">
                    <div id="password_label"></div>
                    <br>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" class="form-control">               
                    <div id="confirm_password_label"></div>
                    <br>
                    <input type="hidden" name="user_id" value="{{$current_user->id}}">
                    <input type="submit" name="update" value="Update password" class="btn btn-success btn-flat" id="update-password">                
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


