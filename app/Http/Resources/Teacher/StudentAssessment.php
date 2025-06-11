<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentAssessment extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'uts_score' => $this->uts_score,
      'uas_score' => $this->uas_score,
      'tugas_score' => $this->tugas_score,
      'activity_score' => $this->activity_score,
      'total_score' => $this->total_score,
      'notes' => $this->notes,
      'notes' => $this->notes,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      'student' => [
        'id' => $this->student->id,
        'name' => $this->student->name,
        'email' => $this->student->email,
        'student_number' => $this->student->student_number,
        'status' => $this->student->status,
      ],
      'subject_schedule' => [
        'id' => $this->subject_schedule->id,
        'day' => $this->subject_schedule->day,
        'subject' => [
          'id' => $this->subject_schedule->id,
          'name' => $this->subject_schedule->subject->name,
        ],
      ],
      'study_year' => [
        'id' => $this->study_year->id,
        'semester' => $this->study_year->semester,
        'year' => $this->study_year->year,
      ],
    ];
  }
}
