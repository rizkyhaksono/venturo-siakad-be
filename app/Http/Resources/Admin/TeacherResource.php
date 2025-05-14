<?php

namespace App\Http\Resources\Admin;

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
      'total_classes' => $this->whenLoaded('homeroomTeachers', function () {
        return $this->homeroomTeachers->unique('class_id')->count();
      }, 0),
      'assigned_classes' => $this->whenLoaded('homeroomTeachers', function () {
        return $this->homeroomTeachers->map(function ($homeroom) {
          return [
            'id' => $homeroom->class->id,
            'name' => $homeroom->class->name,
            'study_year_id' => $homeroom->class->study_year_id,
            'total_rombel' => $homeroom->class->total_rombel,
            'semester' => $homeroom->semester
          ];
        })->unique('id')->values();
      }, []),
      'created_by' => $this->created_by,
      'updated_by' => $this->updated_by,
      'deleted_by' => $this->deleted_by,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
    ];
  }
}
