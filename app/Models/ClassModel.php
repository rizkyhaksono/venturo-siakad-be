<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
  use HasUuids, SoftDeletes, HasFactory;

  protected $table = 'm_class';
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
    return $this->belongsTo(StudyYearModel::class, 'study_year_id', 'id');
  }

  public function subjects(): HasMany
  {
    return $this->hasMany(SubjectModel::class, 'class_id', 'id');
  }

  public function classHistories(): HasMany
  {
    return $this->hasMany(ClassHistoryModel::class, 'class_id', 'id');
  }

  public function subjectSchedules(): HasMany
  {
    return $this->hasMany(SubjectScheduleModel::class, 'class_id', 'id');
  }

  public function homeroomTeachers(): HasMany
  {
    return $this->hasMany(HomeroomTeacherModel::class, 'class_id', 'id');
  }
}
