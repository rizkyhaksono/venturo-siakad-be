<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SPPHistoryModel  extends Model
{
  use HasUuids, SoftDeletes;

  protected $table = 't_spp_history';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
    'user_id',
    'spp_id',
    'amount_paid',
    'payment_date',
    'payment_status',
    'payment_method',
    'proof_payment',
    'created_by',
    'updated_by',
    'deleted_by',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(UserModel::class, 'user_id', 'id');
  }

  public function spp(): BelongsTo
  {
    return $this->belongsTo(SPPModel::class, 'spp_id', 'id');
  }
}
