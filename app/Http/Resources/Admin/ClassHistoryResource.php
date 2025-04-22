<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassHistoryResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'student_id' => $this->student_id,
      'class_id' => $this->class_id,
      'study_year_id' => $this->study_year_id,
      'previous_status' => $this->previous_status,
      'new_status' => $this->new_status,
      'entry_date' => $this->entry_date,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      'created_by' => $this->created_by,
      'updated_by' => $this->updated_by,
      'deleted_by' => $this->deleted_by,
      'student' => [
        'user_id' => $this->student->user_id,
        'name' => $this->student->name,
        'student_number' => $this->student->student_number,
        'birth_date' => $this->student->birth_date,
        'address' => $this->student->address,
        'gender' => $this->student->gender,
        'status' => $this->student->status,
        'created_by' => $this->student->created_by,
        'updated_by' => $this->student->updated_by,
        'deleted_by' => $this->student->deleted_by,
        'created_at' => $this->student->created_at,
        'updated_at' => $this->student->updated_at,
        'deleted_at' => $this->student->deleted_at,
      ],
      'class' => [
        'name' => $this->class->name,
        'study_year_id' => $this->class->study_year_id,
        'created_by' => $this->class->created_by,
        'updated_by' => $this->class->updated_by,
        'deleted_by' => $this->class->deleted_by,
        'created_at' => $this->class->created_at,
        'updated_at' => $this->class->updated_at,
        'deleted_at' => $this->class->deleted_at,
      ],
      'study_year' => [
        'year' => $this->studyYear->year,
        'semester' => $this->studyYear->semester,
        'created_by' => $this->studyYear->created_by,
        'updated_by' => $this->studyYear->updated_by,
        'deleted_by' => $this->studyYear->deleted_by,
        'created_at' => $this->studyYear->created_at,
        'updated_at' => $this->studyYear->updated_at,
        'deleted_at' => $this->studyYear->deleted_at,
      ],
    ];
  }
}
