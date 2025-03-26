<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRoleModel extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'm_user_roles';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'access',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(UsersModel::class, 'role', 'id');
    }
}
