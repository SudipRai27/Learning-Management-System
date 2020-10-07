<div class="modal fade" id="viewSubjectAssessment{{$index}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Assessment Details</h4>
            </div>
            <div class="modal-body">   
                <div class="profile-detail">
                    <div class="main-head" style="text-align:center">               
                    </div>
                    <div class="second-head" style="text-align:center">
                        <span style="color:#333">Subject: {{$record->subject_name}}</span> 
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="background: black; color: white;">
                                    <td>Exam</td>
                                    <td>Full Marks</td>
                                    <td>Obtained Marks</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(json_decode($record->exam_details) as $index => $rec)  
                                    @foreach($rec as $d => $details)    
                                    <tr>
                                        <td>{{$details->exam_name}}</td>
                                        <td>{{$details->full_marks}}</td>
                                        <td>{{$details->obtained_marks}}</td>
                                    </tr>                                    
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                                <tr style="background: black; color: white;">
                                    <td>Assignment</td>
                                    <td>Full Marks</td>
                                    <td>Obtained Marks</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(json_decode($record->assignment_details) as $index => $rec)  
                                    @foreach($rec as $d => $details)    
                                    <tr>
                                        <td>{{$details->title}}</td>
                                        <td>{{$details->full_marks}}</td>
                                        <td>{{$details->obtained_marks}}</td>
                                    </tr>                                    
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>    
                        <p>Assignment Assessable Marks : {{$record->assignment_assessable_marks}}</p>
                        <p>Exam Assessable Marks : {{$record->exam_assessable_marks}}</p>
                        <p>Total Assessable Marks : {{$record->total_assessable_marks}}</p>
                        <p>Total Obtained Marks : {{$record->total_obtained_marks}}</p>
                        <p>Grade : {{$record->grade}}</p>
                        
                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <center>                
                </center>
            </div>
        </div> 
    </div>
</div>					


