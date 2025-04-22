<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class StudyYearResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'year' => $this->year,
      'semester' => $this->semester,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
    ];
  }
}
