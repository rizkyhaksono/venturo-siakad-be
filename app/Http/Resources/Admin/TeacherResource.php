<?php

namespace App\Http\Resources\Admin;

use App\Models\RombelModel;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'user_id' => $this->user_id,
      'name' => $this->name,
      'employee_number' => $this->employee_number,
      'subject' => $this->subject,
      'total_rombel' => RombelModel::where('teacher_id', $this->id)->count(),
      'assigned_rombel' => RombelModel::where('teacher_id', $this->id)->get(),
      'created_by' => $this->created_by,
      'updated_by' => $this->updated_by,
      'deleted_by' => $this->deleted_by,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
    ];
  }
}
