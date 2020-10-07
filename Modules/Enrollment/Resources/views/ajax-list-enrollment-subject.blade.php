@if(count($subjects))
<div class="subject-msg">Available Subjects For This Course</div>
@foreach($subjects as $index=>$subject)
<div class="panel panel-default col-sm-4" style="height:180px;">
	<div class="panel-heading" style="background-color: orange;">{{$subject->subject_name}}</div>
  	<div class="panel-body">	    
  		@if(in_array($subject->id, $enrolled_subject_ids))
  			<div style="text-align: center; font-style: italic; font-weight:bold; font-size: 1rem;">
				<h4>Enrolled</h4>
			</div>	
  		@else
  			<input type="submit" class="btn btn-success add-subject" value="Add" >
  			<input type = "hidden" class="subject_id"  value="{{$subject->id}}">
  			<input type= "hidden" class="subject_name" value="{{$subject->subject_name}}">
  			<input type= "hidden" class="credit_points" value="{{$subject->credit_points}}">
  		@endif  		  		
  	</div>
</div>
@endforeach	
@else
<div class="alert alert-danger alert-dismissable">
	<h4><i class="icon fa fa-warning"></i>NO SUBJECTS AVAILABLE FOR THE STUDENT'S COURSE AND COURSE TYPE</h4>
</div>	
@endif

@include('enrollment::modal.error-msg-modal')	
@include('enrollment::modal.success-msg-modal')	
<script type="text/javascript">
	
	let subject_ids = [];
	
	$(document).ready(function(){

		$('.add-subject').click(function() {
		
		const session_id = $('#session_id').val();
		const subject_id = $(this).next().val();
		const subject_name = $(this).next().next().val(); 
		const credit_points = $(this).next().next().next().val(); 
		if($('#role').val() == "student")		
		{
			checkEnrolledSubjectsForSession($('#secondary_student_id').val(), session_id, subject_id, subject_name, credit_points);	
		}
		else
		{
			checkEnrolledSubjectsForSession($('#student_id').val(), session_id, subject_id, subject_name, credit_points);	
		}
			
		});

		function checkEnrolledSubjectsForSession(student_id, session_id, subject_id, subject_name, credit_points)
		{			
			$.ajax({
				type: 'GET', 
				url: '{{route('check-enrolled-subjects-from-session-id')}}', 
				data: {
					student_id: student_id, 
					session_id: session_id, 
					subject_id: subject_id				
				},
				success: function(res){
					if(res == 'enrolled')
					{
						$('.error-msg').text(subject_name + ' is already enrolled in this semester.');
						$('#errorModal').modal();		
					}
					else
					{
						appendSubjectListToDocument(subject_id, subject_name, credit_points); 	
					}
				}, 
				error:function(jqXHR, textStatus ) {
                    toastr.error("Request Failed : " + textStatus);
                }     
			});
		}

		function appendSubjectListToDocument(subject_id, subject_name, credit_points)
		{	
			if(!subject_ids.some(subject => subject.subject_id === subject_id))
			{
				$('.enrollment-button').show();
				subject_ids.push({subject_id});
				$('#subject-enrollment-table').append("<tr><td>" + subject_name + " - " +credit_points +" Credit Points<input type='hidden' name='subject_ids[]' value =" + subject_id+ "></td><td><button data-toggle='tooltip' class='btn btn-danger remove-subject-btn' type='button' data-original-title='Remove' > <i class='fa fa-fw fa-trash'></i></button></a></td></tr>");	
				$('.success-msg').text(subject_name + ' has been added in the enrollment list');		
				$('#successModal').modal();
			}
			else
			{
				$('.error-msg').text(subject_name + ' is already added in the enrollment list');
				$('#errorModal').modal();		
			}
		
		}			
	});

	$(document).on('click', '.remove-subject-btn', function(e){
		e.preventDefault();
        const current_subject_id = $(this).parent().prev().find('input').val(); 

        //remove subject_id from array
        for (var i =0; i < subject_ids.length; i++)
   		if (subject_ids[i].subject_id === current_subject_id) {
      		subject_ids.splice(i,1);
      		break;
   		}     
   		//hide enrollment button
   		if(subject_ids.length === 0)
   		{
   			$('.enrollment-button').hide();
   		}        
		$(this).parent().parent().remove();		
    });
</script>