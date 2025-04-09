<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectHourModel extends Model
{
  use HasUuids, SoftDeletes;

  protected $table = 'm_subject_hour';
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
    return $this->belongsTo(SubjectScheduleModel::class, 'subject_hour_id', 'id');
  }
}
