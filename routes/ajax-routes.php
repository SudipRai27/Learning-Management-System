<?php


Route::get('get-courses-from-course-type', [
	'as' => 'get-courses-from-course-type',  
	'uses' => '\App\Http\Controllers\AjaxController@getCoursesFromCourseType'
]);

Route::get('get-ajax-search-results', [
	'as' => 'get-ajax-search-results', 
	'uses' => '\App\Http\Controllers\AjaxController@getSearchResults'
]);

Route::get('get-student-autocomplete', [
	'as' => 'get-student-autocomplete', 
	'uses' => '\App\Http\Controllers\AjaxController@getAutoCompleteStudent'
]);

Route::get('get-subjects-from-student-id-current-course-and-course-type', [
	'as' => 'get-subjects-from-student-id-current-course-and-course-type', 
	'uses' => '\App\Http\Controllers\AjaxController@getSubjectFromStudentIdCurrentCourseAndCourseType'
]);

Route::get('get-current-course-and-type-from-student-id', [
	'as' => 'get-current-course-and-type-from-student-id', 
	'uses' => '\App\Http\Controllers\AjaxController@getCourseAndCourseTypeFromStudentID'
]);

Route::get('check-enrolled-subjects-from-session-id', [
	'as' => 'check-enrolled-subjects-from-session-id', 
	'uses' => '\App\Http\Controllers\AjaxController@getCheckEnrolledSubjects'
]);

Route::get('get-all-course-type-select-box', [
	'as' => 'get-all-course-type-select-box', 
	'uses' => '\App\Http\Controllers\AjaxController@getCourseTypeSelectBox'
]);

Route::get('get-enrollment-records-from-student-id-session-id', [
	'as' => 'get-enrollment-records-from-student-id-session-id', 
	'uses' => '\App\Http\Controllers\AjaxController@getEnrollmentRecordsFromStudentIdSessionId'
]);

Route::post('remove-enrolled-subjects-from-studentid-sessionid-enrollmentId-subjectId', [
	'as' => 'remove-enrolled-subjects-from-studentid-sessionid-enrollmentId-subjectId', 
	'uses' => '\App\Http\Controllers\AjaxController@postDeleteEnrollmentRecordsFromIds'

]);

Route::post('assign-teacher-ajax-post', [
	'as' => 'assign-teacher-ajax-post', 
	'uses' => '\App\Http\Controllers\AjaxController@postAjaxAssignTeacher'
]);

Route::get('check-assigned-teacher',[
	'as' => 'check-assigned-teacher', 
	'uses' => '\App\Http\Controllers\AjaxController@getCheckAssignedTeacher'
]);

Route::get('get-subject-from-course-type-and-course', [
	'as' => 'get-subject-from-course-type-and-course', 
	'uses' => '\App\Http\Controllers\AjaxController@getSubjectFromCourseTypeAndCourse'
]);

Route::get('get-teacher-by-type-autocomplete', [
	'as' => 'get-teacher-by-type-autocomplete', 
	'uses' => '\App\Http\Controllers\AjaxController@getTeacherbyTypeAutoComplete'
]);

Route::get('get-assigned-teacher-list-from-session-id-teacher-id',[
	'as' => 'get-assigned-teacher-list-from-session-id-teacher-id', 
	'uses' => '\App\Http\Controllers\AjaxController@getAssignedTeachersFromSessionAndTeacherID'
]);

Route::post('ajax-delete-assigned-teacher', [
	'as' => 'ajax-delete-assigned-teacher', 
	'uses' => '\App\Http\Controllers\AjaxController@postAjaxDeleteAssignedTeacher'
]);

Route::get('get-student-from-course-type-and-course' , [
	'as' => 'get-student-from-course-type-and-course',  
	'uses' => '\App\Http\Controllers\AjaxController@getStudentFromCourseTypeAndCourse'
]);

Route::get('ajax-get-assigned-teacher-by-class-type', [
	'as' => 'ajax-get-assigned-teacher-by-class-type', 
	'uses' => '\App\Http\Controllers\AjaxController@getAssignedTeacherByClassType'
]);

Route::get('ajax-get-room-by-type', [
	'as' => 'ajax-get-room-by-type', 
	'uses' => '\App\Http\Controllers\AjaxController@getAjaxRoomByRoomType'
]);

Route::get('ajax-check-teacher-classes', [
	'as' => 'ajax-check-teacher-classes',
	'uses' => '\App\Http\Controllers\AjaxController@getAjaxCheckTeacherClasses'
	
]);

Route::get('/ajax-check-current-session', [
	'as' => 'ajax-check-current-session', 
	'uses' => '\App\Http\Controllers\AjaxController@getCheckCurrentSession'
]);

Route::get('/ajax-get-enrolled-student-from-session', [
	'as' => 'ajax-get-enrolled-student-from-session', 
	'uses' => '\App\Http\Controllers\AjaxController@getEnrolledStudentsFromSession'
]);

Route::get('/ajax-get-teacher-assigned-subjects-from-session', [
	'as' => 'ajax-get-teacher-assigned-subjects-from-session', 
	'uses' => '\App\Http\Controllers\AjaxController@getAjaxTeacherAssignedSubjectsFromSession'
]);


Route::get('/ajax-get-exam-select-list-from-session', [
	'as' => 'ajax-get-exam-select-list-from-session', 
	'uses' => '\App\Http\Controllers\AjaxController@getAjaxExamSelectListFromSession'
]);

Route::post('/ajax-update-exam-marks', [
	'as' => 'ajax-update-exam-marks', 
	'uses' => '\App\Http\Controllers\AjaxController@postUpdateExamMarks'
]);

Route::get('/ajax-get-assignment-select-list-from-session', [
	'as' => 'ajax-get-assignment-select-list-from-session', 
	'uses' => '\App\Http\Controllers\AjaxController@getAjaxAssignmentSelectList'
]);

Route::post('/ajax-update-assignment-marks', [
	'as'=> 'ajax-update-assignment-marks', 
	'uses' => '\App\Http\Controllers\AjaxController@postUpdateAssignmentMarks'
]);


Route::get('/ajax-get-individual-student-result', [
	'as' => 'ajax-get-individual-student-result', 
	'uses' => '\App\Http\Controllers\AjaxController@getAjaxIndividualStudentResult'
]);

Route::get('/ajax-get-enrolled-subjects-dashboard-student-teacher', [
	'as' => 'ajax-get-enrolled-subjects-dashboard-student-teacher', 
	'uses' => '\App\Http\Controllers\AjaxController@getEnrolledSubjectsDashboard'
]);

Route::get('/check-setting-according-to-session', [
	'as' => 'check-setting-according-to-session', 
	'uses' => '\App\Http\Controllers\AjaxController@getCheckSessionSettings'
]);
?>