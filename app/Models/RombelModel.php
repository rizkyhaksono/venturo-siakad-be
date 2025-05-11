<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RombelModel extends Model
{
  use HasFactory, SoftDeletes, HasUuids;

  protected $table = 'm_rombel';
  protected $primaryKey = 'id';
  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'name',
    'class_id'
  ];

  public function class()
  {
    return $this->belongsTo(ClassModel::class, 'class_id', 'id');
  }
}
