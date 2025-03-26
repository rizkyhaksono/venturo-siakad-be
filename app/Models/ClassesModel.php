<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassesModel extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'm_classes';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'study_year_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function studyYear(): BelongsTo
    {
        return $this->belongsTo(StudyYearsModel::class, 'study_year_id', 'id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(SubjectsModel::class, 'class_id', 'id');
    }

    public function classHistories(): HasMany
    {
        return $this->hasMany(ClassHistoriesModel::class, 'class_id', 'id');
    }

    public function subjectSchedules(): HasMany
    {
        return $this->hasMany(SubjectSchedulesModel::class, 'class_id', 'id');
    }

    public function homeroomTeachers(): HasMany
    {
        return $this->hasMany(HomeroomTeachersModel::class, 'class_id', 'id');
    }
}
