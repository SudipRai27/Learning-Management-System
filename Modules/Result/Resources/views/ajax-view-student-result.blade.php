@if(count($result))
<div class="table-responsive result-table">
    <div class="table-header">Displaying Results </div>
    <table class="table">
        <thead>
            <tr style="background:#252551; color:white;">
                <th>Subject</th>                
                <th>Total Obtained Marks</th>
                <th>Total Assessable Marks</th>
                <th>Grade</th>
            </tr>
            <tbody>
                @foreach($result as $index => $record)
                <tr>                            
                    <td>
                        <a data-toggle="modal" data-target="#viewSubjectAssessment{{$index}}" data-title="View Assessment details" data-message="View Assessment">
                        {{$record->subject_name}}
                        </a> 
                        @include('result::modal.subject-assessment-details-modal') 
                        </td>                                    
                    <td>{{$record->total_obtained_marks}}</td>
                    <td>{{$record->total_assessable_marks}}</td>                
                    <td>{{$record->grade}}</td>
                </tr>
                @endforeach
            </tbody>
        </thead>
    </table>
</div>
@else
    <div class="alert alert-danger alert-dismissable">
        <h4><i class="icon fa fa-warning"></i>Results not available</h4>
    </div>
@endif
