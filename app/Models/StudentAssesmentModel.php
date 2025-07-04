<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAssesmentModel extends Model
{
  use HasUuids, SoftDeletes;

  protected $table = 'm_student_assesments';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'student_id',
    'subject_schedule_id',
    'uts_score',
    'uas_score',
    'tugas_score',
    'activity_score',
    'total_score',
    'notes',
    'study_year_id',
  ];

  public function student(): BelongsTo
  {
    return $this->belongsTo(StudentModel::class, 'student_id', 'id');
  }

  public function subjectSchedule(): BelongsTo
  {
    return $this->belongsTo(SubjectScheduleModel::class, 'subject_schedule_id', 'id');
  }

  public function studyYear(): BelongsTo
  {
    return $this->belongsTo(StudyYearModel::class, 'study_year_id', 'id');
  }

  public function teacher(): BelongsTo
  {
    return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id');
  }
}
