<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SPPModel  extends Model
{
  use HasUuids, SoftDeletes;

  protected $table = 'm_spp';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'name',
    'jenis_biaya',
    'study_year_id',
    'total',
    'created_by',
    'updated_by',
    'deleted_by',
  ];

  public function studyYear(): BelongsTo
  {
    return $this->belongsTo(StudyYearModel::class, 'study_year_id', 'id');
  }
}
