<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectsModel extends Model
{
    use HasUuids;

    protected $table = 'm_subjects';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'study_year_id',
        'class_id',
        'teacher_id',
        'created_by'
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

    public function schedules(): HasMany
    {
        return $this->hasMany(SubjectSchedulesModel::class, 'subject_id', 'id');
    }
}
