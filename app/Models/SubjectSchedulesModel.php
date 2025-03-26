<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectSchedulesModel extends Model
{
    use HasUuids;

    protected $table = 'm_subject_schedules';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'subject_hour_id'
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassesModel::class, 'class_id', 'id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(SubjectsModel::class, 'subject_id', 'id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function subjectHour(): BelongsTo
    {
        return $this->belongsTo(SubjectHoursModel::class, 'subject_hour_id', 'id');
    }
}
