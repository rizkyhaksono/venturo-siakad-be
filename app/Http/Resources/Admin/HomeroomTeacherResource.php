<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class HomeroomTeacherResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'previous_status' => $this->previous_status,
      'new_status' => $this->new_status,
      'entry_date' => $this->entry_date,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      'class' => [
        'name' => $this->class->name
      ],
      'study_year' => [
        'year' => $this->studyYear->year,
        'semester' => $this->studyYear->semester
      ]
    ];
  }
}
