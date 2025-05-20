<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'student_number' => $this->student_number,
      'birth_date' => $this->birth_date,
      'address' => $this->address,
      'gender' => $this->gender,
      'status' => $this->status,
      'created_by' => $this->created_by,
      'updated_by' => $this->updated_by,
      'deleted_by' => $this->deleted_by,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      'user' => [
        'name' => $this->user->name,
        'email' => $this->user->email,
        'phone_number' => $this->user->phone_number,
        'photo' => $this->user->photo,
        'wali' => $this->user->wali,
        'pekerjaan' => $this->user->pekerjaan,
        'birth_date' => $this->user->birth_date,
        'address' => $this->user->address,
        'gender' => $this->user->gender,
      ],
    ];
  }
}
