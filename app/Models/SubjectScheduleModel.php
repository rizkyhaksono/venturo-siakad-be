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
    'subject_hour_id',
    'rombel_id',
    'day'
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

  public function rombel()
  {
    return $this->belongsTo(RombelModel::class, 'rombel_id');
  }

  // get student who is in this subject schedule
  public function students()
  {
    return $this->belongsToMany(StudentModel::class, 'm_student_subject_schedule', 'subject_schedule_id', 'student_id');
  }
}
