<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectHoursModel extends Model
{
  use HasUuids;

  protected $table = 'm_subject_hours';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'start_hour',
    'start_time',
    'end_time',
    'created_by',
    'updated_by',
    'deleted_by',
  ];

  public function subjectSchedules(): BelongsTo
  {
    return $this->belongsTo(SubjectSchedulesModel::class, 'subject_hour_id', 'id');
  }
}
