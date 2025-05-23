<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassHistoryModel extends Model
{
  use HasUuids, SoftDeletes;

  protected $table = 'm_class_history';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'student_id',
    'class_id',
    'study_year_id',
    'previous_status',
    'new_status',
    'entry_date'
  ];

  public function student(): BelongsTo
  {
    return $this->belongsTo(StudentModel::class, 'student_id', 'id');
  }

  public function class(): BelongsTo
  {
    return $this->belongsTo(ClassModel::class, 'class_id', 'id');
  }

  public function studyYear(): BelongsTo
  {
    return $this->belongsTo(StudyYearModel::class, 'study_year_id', 'id');
  }
}
