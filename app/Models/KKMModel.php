<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KKMModel extends Model
{
  use HasUuids, SoftDeletes;

  protected $table = 'm_kkm';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'subject_id',
    'min_score',
    'description',
  ];

  public function subject(): BelongsTo
  {
    return $this->belongsTo(SubjectModel::class, 'subject_id', 'id');
  }
}
