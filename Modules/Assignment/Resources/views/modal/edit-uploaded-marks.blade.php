<div class="modal fade" id="editUplodedMarks{{$record->student_id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Update Marks</h4>
                
            </div>
            
            <div class="modal-body">     
                <p style="text-align: center;">Student : 
                    {{$record->name .' / '. $record->uniqueId}}
                </p>
                    <label>Obtained Marks:</label>
                    <input type="" name="" class="form-control current-obtained-marks" value="{{$record->obtained_marks}}"
                    readonly>
                    <label>New Marks:</label>
                    <input type="number" name="new_marks" 
                    step="0.01" min="0" max="{{$assignment_full_marks}}" 
                    class="form-control new-marks" placeholder="New Marks">
                    <input type="hidden" name="student_id" value="{{$record->student_id}}" class="student-id">                    
            </div>  
            <div class="modal-footer"> 
                <input type="submit" value="Update" class="btn btn-success btn-flat edit-marks-btn"> 
            </div>
            
        </div> 
    </div>
</div>		
