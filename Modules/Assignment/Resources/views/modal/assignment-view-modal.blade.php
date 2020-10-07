<div class="modal fade" id="viewAssignment{{$assignment['assignment_id']}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Assignment Details</h4>
            </div>
            <div class="modal-body">  
                <div class="profile-detail">
                    <div class="second-head" style="text-align:center">               
                        <span>Session: {{ (new \Modules\AcademicSession\Entities\AcademicSession)->getSessionNameFromID($assignment['session_id']) }}</span><br>
                           <span> {{ (new \Modules\Subject\Entities\Subject)->getSubjectNameFromId($assignment['subject_id']) }}</span>
                    </div>
                    <div class="second-head" style="text-align:center">
                        <span style="color:#333">Title: {{$assignment['title']}}</span> 
                    </div>
                    <ul>
                        <li>
                            <label> Description: </label><span class="text-green"> {{$assignment['description']}}</span>
                        </li>
                        <li>
                            <label>Marks:</label> <span class="text-green"> {{$assignment['marks']}}</span>
                        </li>
                        <li>
                            <label>Sort Order:</label> <span class="text-green">{{$assignment['sort_order']}}</span>
                        </li>               
                        <li>
                            <label>Submission Date:</label> <span class="text-green"> {{$assignment['submission_date']}} </span>
                        </li>
                        <li>
                            <label>Files:</label> 
                            <br>
                            <span class="text-green"> 
                                @if(count($assignment['resources']))
                                    @foreach($assignment['resources'] as $key => $resource)
                                        <a href="{{$resource['s3_url']}}">{{$resource['filename']}}</a><br>
                                    @endforeach
                                @else
                                    No Files
                                @endif                              
                            </span>
                        </li>
                        
                    </ul>
                </div>                    
            </div>
            <div class="modal-footer">
                <center>                
                </center>
            </div>
        </div> 
    </div>
</div>					


