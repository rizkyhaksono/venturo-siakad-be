<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'study_year_id' => $this->study_year_id,
      'created_by' => $this->created_by,
      'updated_by' => $this->updated_by,
      'deleted_by' => $this->deleted_by,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      'study_year' => [
        'year' => $this->studyYear->year,
        'semester' => $this->studyYear->semester,
        'created_by' => $this->studyYear->created_by,
        'updated_by' => $this->studyYear->updated_by,
        'deleted_by' => $this->studyYear->deleted_by,
        'created_at' => $this->studyYear->created_at,
        'updated_at' => $this->studyYear->updated_at,
        'deleted_at' => $this->studyYear->deleted_at,
      ],
    ];
  }
}
