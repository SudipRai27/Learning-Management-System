<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
	protected $table = 'course_type'; 

    protected $fillable = ['course_type', 'description'];

    public static $rules = [
    	'course_type' => 'required', 
    	
    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }


    public function getCourse()
    {
    	return $this->belongsTo('\Modules\Course\Entities\Course'); 
    }
}
