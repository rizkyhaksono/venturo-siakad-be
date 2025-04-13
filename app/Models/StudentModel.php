<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentModel extends Model
{
  use HasUuids, SoftDeletes;

  protected $table = 'm_student';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'user_id',
    'name',
    'student_number',
    'birth_date',
    'address',
    'gender',
    'status',
    'created_by',
    'updated_by',
    'deleted_by'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(UserModel::class, 'user_id', 'id');
  }

  public function classHistories(): HasMany
  {
    return $this->hasMany(ClassHistoryModel::class, 'student_id', 'id');
  }
}
