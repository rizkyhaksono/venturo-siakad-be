<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyYearModel extends Model
{
  use HasUuids, SoftDeletes;

  protected $table = 'm_study_year';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'year',
    'semester',
    'status',
    'created_by',
    'updated_by',
    'deleted_by'
  ];

  public function classes(): HasMany
  {
    return $this->hasMany(ClassModel::class, 'study_year_id', 'id');
  }

  public function subjects(): HasMany
  {
    return $this->hasMany(SubjectModel::class, 'study_year_id', 'id');
  }

  public function classHistories(): HasMany
  {
    return $this->hasMany(ClassHistoryModel::class, 'study_year_id', 'id');
  }
}
