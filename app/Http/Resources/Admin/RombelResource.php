<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RombelResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'class' => [
        'id' => $this->class->id,
        'name' => $this->class->name,
        'total_rombel' => $this->class->total_rombel,
      ],
      'study_year' => [
        'id' => $this->studyYear->id,
        'name' => $this->studyYear->name,
      ],
      'teacher' => [
        'id' => $this->teacher->id,
        'name' => $this->teacher->name,
      ],
      'subject' => [
        'id' => $this->subject->id,
        'name' => $this->subject->name,
      ],
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at
    ];
  }
}
