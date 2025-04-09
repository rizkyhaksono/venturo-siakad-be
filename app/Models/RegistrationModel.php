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
    'user_id',
    'status'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(UserModel::class, 'user_id', 'id');
  }

  public function student(): BelongsTo
  {
    return $this->belongsTo(StudentModel::class, 'user_id', 'id');
  }

  public function teacher(): BelongsTo
  {
    return $this->belongsTo(TeacherModel::class, 'user_id', 'id');
  }
}
