<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
	protected $table = 'courses'; 

    protected $fillable = ['course_title', 'course_type_id','description'];

    public static $rules = [
    	'course_title' => 'required', 
    	'course_type_id' => 'required'
    	
    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    
    public function getCourseType()
    {
    	return $this->hasOne('\Modules\Course\Entities\CourseType'); 
    }
}
