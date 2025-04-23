<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassHistoryResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->class->name ?? null,  // Get name from class relationship
      'previous_status' => $this->previous_status,
      'new_status' => $this->new_status,
      'entry_date' => $this->entry_date,
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
