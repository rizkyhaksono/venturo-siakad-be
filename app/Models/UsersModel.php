<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;

class UsersModel extends Authenticatable
{
    use HasApiTokens;
    use HasUuids;
    use SoftDeletes;

    protected $table = 'm_users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'password',
        'email',
        'role',
        'photo',
        'phone_number'
    ];

    protected $hidden = [
        'password'
    ];

    public function userRole(): BelongsTo
    {
        return $this->belongsTo(UserRoleModel::class, 'role', 'id');
    }

    public function student(): HasOne
    {
        return $this->hasOne(StudentsModel::class, 'user_id', 'id');
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(TeachersModel::class, 'user_id', 'id');
    }

    public function isHasRole($roles)
    {
        $requiredRoles = explode('|', $roles); // e.g., ["admin"]
        $userRoleName = $this->userRole ? $this->userRole->name : null; // Fetch the role name (e.g., "Admin")

        if (!$userRoleName) {
            return false; // No role assigned to the user
        }

        return in_array(strtolower($userRoleName), array_map('strtolower', $requiredRoles)); // Case-insensitive comparison
    }
}
