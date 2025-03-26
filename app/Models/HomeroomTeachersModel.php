<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeroomTeachersModel extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'm_homeroom_teachers';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'teacher_id',
        'class_id',
        'study_year_id',
        'semester',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(TeachersModel::class, 'teacher_id', 'id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassesModel::class, 'class_id', 'id');
    }

    public function studyYear(): BelongsTo
    {
        return $this->belongsTo(StudyYearsModel::class, 'study_year_id', 'id');
    }
}
