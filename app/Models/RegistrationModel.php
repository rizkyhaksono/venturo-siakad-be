<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationModel extends Model
{
  use HasUuids, SoftDeletes;

  protected $table = 'm_registration';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'student_id',
    'status'
  ];

  public function student(): BelongsTo
  {
    return $this->belongsTo(StudentModel::class, 'student_id', 'id');
  }
}
