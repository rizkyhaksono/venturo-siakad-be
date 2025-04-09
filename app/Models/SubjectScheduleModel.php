<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectScheduleModel extends Model
{
  use HasUuids;

  protected $table = 'm_subject_schedule';
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
    return $this->belongsTo(ClassModel::class, 'class_id', 'id');
  }

  public function subject(): BelongsTo
  {
    return $this->belongsTo(SubjectModel::class, 'subject_id', 'id');
  }

  public function teacher(): BelongsTo
  {
    return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id');
  }

  public function subjectHour(): BelongsTo
  {
    return $this->belongsTo(SubjectHourModel::class, 'subject_hour_id', 'id');
  }
}
