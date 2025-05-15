<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RombelModel extends Model
{
  use HasFactory, SoftDeletes, HasUuids;

  protected $table = 'm_rombel';
  protected $primaryKey = 'id';
  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'name',
    'class_id',
    'study_year_id',
    'teacher_id',
    'subject_schedule_id',
    'student_id'
  ];

  public function class()
  {
    return $this->belongsTo(ClassModel::class, 'class_id', 'id');
  }

  public function studyYear()
  {
    return $this->belongsTo(StudyYearModel::class, 'study_year_id', 'id');
  }

  public function teacher()
  {
    return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id');
  }

  public function student()
  {
    return $this->belongsTo(StudentModel::class, 'student_id', 'id');
  }

  public function subjectSchedules()
  {
    return $this->belongsTo(SubjectScheduleModel::class, 'subject_schedule_id', 'id');
  }
}
