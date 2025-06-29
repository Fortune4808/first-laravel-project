<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'staffId' => $this->staffId,
            'firstName' => $this->firstName,
            'middleName' => $this->middleName,
            'lastName' => $this->lastName,
            'emailAddress' => $this->emailAddress,
            'mobileNumber' => $this->mobileNumber,
            'gender' => [
                'genderName' => $this->gender->genderName ?? null,
                'genderId' => $this->gender->id ?? null,
            ],
            'status' => [
                'statusName' => $this->status->statusName ?? null,
                'statusId' => $this->status->id ?? null,
            ],
            'role' => [
                'roleName' => $this->roles->first()?->name,
                'roleId' => $this->roles->first()?->id,
            ],
            'permissions' => $this->getAllPermissions()->pluck('name'),
            'passportUrl' => asset('storage/passports/admin/' . $this->passport),
            'createdAt' => $this->created_at
        ];
    }
}
