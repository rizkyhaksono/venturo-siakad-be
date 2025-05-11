<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectModel extends Model
{
  use HasUuids;

  protected $table = 'm_subject';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'name',
    'created_by'
  ];

  // public function teacher(): BelongsTo
  // {
  //   return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id');
  // }

  // public function class(): BelongsTo
  // {
  //   return $this->belongsTo(ClassModel::class, 'class_id', 'id');
  // }

  // public function studyYear(): BelongsTo
  // {
  //   return $this->belongsTo(StudyYearModel::class, 'study_year_id', 'id');
  // }

  // public function schedules(): HasMany
  // {
  //   return $this->hasMany(SubjectScheduleModel::class, 'subject_id', 'id');
  // }
}
