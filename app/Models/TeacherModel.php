<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherModel extends Model
{
  use HasUuids, SoftDeletes;

  protected $table = 'm_teacher';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'user_id',
    'name',
    'employee_number',
    'subject',
    'created_by',
    'updated_by',
    'deleted_by'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(UserModel::class, 'user_id', 'id');
  }

  public function subjects(): HasMany
  {
    return $this->hasMany(SubjectModel::class, 'teacher_id', 'id');
  }

  public function homeroomTeachers(): HasMany
  {
    return $this->hasMany(HomeroomTeacherModel::class, 'teacher_id', 'id');
  }
}
