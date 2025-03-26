<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyYearsModel extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'm_study_years';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'year',
        'semester',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function classes(): HasMany
    {
        return $this->hasMany(ClassesModel::class, 'study_year_id', 'id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(SubjectsModel::class, 'study_year_id', 'id');
    }

    public function classHistories(): HasMany
    {
        return $this->hasMany(ClassHistoriesModel::class, 'study_year_id', 'id');
    }
}
