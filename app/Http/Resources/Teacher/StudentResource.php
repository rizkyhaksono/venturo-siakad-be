<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
  public function toArray($request)
  {
    $latestClassHistory = $this->classHistory->last();
    return [
      'id' => $this->id,
      'name' => $this->name,
      'student_number' => $this->student_number,
      'birth_date' => $this->birth_date,
      'address' => $this->address,
      'gender' => $this->gender,
      'status' => $this->status,
      'created_by' => $this->created_by,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      'classHistory' => $latestClassHistory ? [
        'previous_status' => $latestClassHistory->previous_status,
        'new_status' => $latestClassHistory->new_status,
        'entry_date' => $latestClassHistory->entry_date,
      ] : null,
    ];
  }
}
