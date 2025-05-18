<?php

namespace App\Http\Resources\Teacher;

use  Illuminate\Http\Resources\Json\JsonResource;

class SubjectScheduleResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'day' => $this->day,
      'class' => [
        'name' => $this->class->name ?? null,
      ],
      'subject' => [
        'name' => $this->subject->name ?? null,
      ],
      'teacher' => [
        'name' => $this->teacher->name ?? null,
        'employee_number' => $this->teacher->employee_number ?? null,
      ],
      'subject_hour' => [
        'start_hour' => $this->subjectHour->start_hour ?? null,
        'start_time' => $this->subjectHour->start_time ?? null,
        'end_time' => $this->subjectHour->end_time ?? null,
      ]
    ];
  }
}
