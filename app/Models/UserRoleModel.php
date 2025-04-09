<?php

namespace App\Models;

use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRoleModel extends Model implements CrudInterface
{
  use HasUuids, SoftDeletes;

  protected $table = 'm_user_roles';
  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string';
  public $timestamps = true;

  protected $fillable = [
    'name',
    'access',
  ];

  public function drop(string $id)
  {
    return $this->find($id)->delete();
  }

  public function edit(array $payload, string $id)
  {
    return $this->find($id)->update($payload);
  }

  public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
  {
    $query = $this->query();

    // Apply name filter if exists
    if (!empty($filter['name'])) {
      $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
    }

    // Get total before pagination
    $total = $query->count();

    // Apply sorting
    if (!empty($sort)) {
      $query->orderByRaw($sort);
    } else {
      $query->orderBy('created_at', 'desc');
    }

    // Apply pagination if itemPerPage is specified
    if ($itemPerPage > 0) {
      $query->forPage($page, $itemPerPage);
    }

    // Get the data
    $data = $query->get();

    return [
      'data' => $data,
      'total' => $total
    ];
  }

  public function getById(string $id)
  {
    return $this->find($id);
  }

  public function store(array $payload)
  {
    return $this->create($payload);
  }

  public function users(): HasMany
  {
    return $this->hasMany(UsersModel::class, 'role', 'id');
  }
}
