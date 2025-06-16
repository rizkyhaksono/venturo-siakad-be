<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectModel extends Model
{
  use HasUuids;

  protected $table = 'm_subject';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'name',
    'created_by'
  ];

  public function kkm(): HasMany
  {
    return $this->hasMany(KKMModel::class, 'subject_id', 'id');
  }
}
