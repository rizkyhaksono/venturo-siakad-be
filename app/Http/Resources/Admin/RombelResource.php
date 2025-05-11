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
      "class" => [
        "name" => $this->class->name,
        "total_rombel" => $this->class->total_rombel,
      ],
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at
    ];
  }
}
