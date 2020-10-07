<div class="modal fade text-alert" id="viewSubject{{$d->id}}" role="dialog" aria-labelledby="viewSubjectLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-info">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">SUBJECT UNIT DETAILS </h4>
            </div>
            <div class="modal-body">                 
                 <p>    
                    <b>Subject Name</b> :<h4><i> {{ $d->subject_name }}</i></h4>
                    <b>Credit Points</b> :<h4><i> {{ $d->credit_points }}</i></h4>
                    <b>Description </b>:<h4><i>{{ $d->description }}</i></h4>
                    <b>Is Graded </b>:<h4><i>{{ $d->is_graded }}</i></h4>
                    <b>Course Type </b>:<h4><i>{{ $d->course_type }}</i></h4>
                    <b>Course  ID</b>:<h4><i>{{ $d->course_id }}</i></h4>
                    <b>Full Marks </b>:<h4><i>{{ $d->full_marks }}</i></h4>
                    <b>Pass Marks </b>:<h4><i>{{ $d->pass_marks }}</i></h4>
                 </p>
            </div>
            <div class="modal-footer">                
            </div>
        </div> 
    </div>
</div>					


