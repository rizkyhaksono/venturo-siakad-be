<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectHourResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'start_hour' => $this->start_hour,
      'start_time' => $this->start_time,
      'end_time' => $this->end_time,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
    ];
  }
}
