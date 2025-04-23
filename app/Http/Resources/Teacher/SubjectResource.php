<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name ?? null,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      'study_year' => [
        'year' => $this->studyYear->year,
        'semester' => $this->studyYear->semester,
        'created_at' => $this->studyYear->created_at,
        'updated_at' => $this->studyYear->updated_at,
        'deleted_at' => $this->studyYear->deleted_at,
      ],
    ];
  }
}
