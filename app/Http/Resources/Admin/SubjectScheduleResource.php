<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectScheduleResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'day' => $this->day,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      "class" => [
        "name" => $this->class->name,
        "study_year" => [
          "semester" => $this->class->studyYear->semester,
        ]
      ],
      "subject" => [
        "name" => $this->subject->name,
      ],
      "teacher" => [
        "name" => $this->teacher->name,
        "employee_number" => $this->teacher->employee_number,
        "subject" => $this->teacher->subject,
      ],
      "subject_hour" => [
        "start_hour" => $this->subjectHour->start_hour,
        "start_time" => $this->subjectHour->start_time,
        "end_time" => $this->subjectHour->end_time,
      ]
    ];
  }
}
