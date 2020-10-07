<?php

namespace Modules\Exam\Entities;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['session_id', 'exam_name', 'marks', 'start_date', 'end_date'];

    protected $table = 'exam'; 

    protected $guarded = ['created_at', 'updated_at']; 

    public static function getTableName()
    {
        return with(new Static)->getTable();
    }

    public static function getValidationRules() {
    	return [
    		'session_id' => 'required', 	 
    		'exam_name' => 'required', 
    		'marks' => 'required|min:1|numeric', 
    		'start_date' => 'required',
	        'end_date' => 'required|after:start_date'	        
    	];
    }

    public static function getValidationMessages() {
    	return [
    		'session_id.required' => 'Please select the session you want to create / update the exam for.', 
       		'exam_name' => 'Please enter the exam name', 
    		'marks' => 'Please specify the marks and it should be greater than zero', 
    		'start_date.required' => 'Please select start date', 
    		'end_date.required' => 'Please select the end date'
    	];
    }

    public function createExam($input)
    {
    	return Exam::create([
    		'session_id' => $input['session_id'], 
    		'exam_name' => $input['exam_name'], 
    		'start_date' => $input['start_date'], 
    		'end_date' => $input['end_date'], 
    		'marks' => $input['marks']
    	]);
    }

    public function getExamsFromSession($session_id)
    {    	
    	$session_table = \Modules\AcademicSession\Entities\AcademicSession::getTableName();  

    	$exam_list = \DB::table($this->table)
    				->join($session_table, $session_table.'.id','=',$this->table.'.session_id')
    				->select($this->table. '.*', $session_table.'.session_name');

    	if($session_id != 'all')
    	{
    		$exam_list = $exam_list->where($this->table.'.session_id', $session_id);

    	}

    	$exam_list = $exam_list->orderBy('created_at', 'DESC')->get(); 
    	return $exam_list;
    }

    public function getIndividualExam($exam_id)
    {
        return $this->findorFail($exam_id);
    }

    public function updateExam($exam_id, $input)
    {
        return Exam::where('id', $exam_id)
                    ->update([
                        'session_id' => $input['session_id'], 
                        'exam_name' => $input['exam_name'], 
                        'start_date' => $input['start_date'], 
                        'end_date' => $input['end_date'], 
                        'marks' => $input['marks']
                    ]);
    }

    public function deleteExam($exam_id)
    {
        return Exam::where('id', $exam_id)->delete(); 
    }

    public function getIndividualExamFullMarks($exam_id)
    {
        $exam = $this->getIndividualExam($exam_id); 
        return $exam->marks;
    }
}
