@extends('backend.submain')
@section('custom-css')
<link href="{{ asset('public/sms/assets/css/form.css')}}" rel="stylesheet" type="text/css"> 

@endsection
@section('content')
<div class="form-header2">Result</div>
<div class="text-center" style="font-size: 2rem; margin-bottom:1rem;">
    <?php $student = (new \Modules\Student\Entities\Student)->getStudentDetailsFromStudentId($student_id) ?>
    @if(!is_null($student))
    {{$student->name}} / 
    {{$student->student_id}}
    @endif
</div>
<div class="table-responsive">
    <table class="table result-table">
        <thead>
            <tr style="background:#202079; color:white;">
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
@endsection
