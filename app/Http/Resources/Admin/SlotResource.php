<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slotName' => $this->slotName,
            'location' => [
                'locationName' => $this->locations->locationName,
                'locationId' => $this->locations->id,
            ],
            'status' => [
                'statusName' => $this->status->statusName,
                'statusId' => $this->statusId,
            ],
            'createdBy' => $this->createdBy,
            'created_at' => $this->created_at,
        ];
    }
}
