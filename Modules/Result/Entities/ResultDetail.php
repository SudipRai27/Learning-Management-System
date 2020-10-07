<?php

namespace Modules\Result\Entities;

use Illuminate\Database\Eloquent\Model;

class ResultDetail extends Model
{
    protected $fillable = ['result_id', 'assignment_details', 'exam_details'];

    protected $table = 'result_details';

    public static function getTableName()
    {
        return with(new Static)->getTable();
    }
}
