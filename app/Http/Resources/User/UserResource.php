<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'userId' => $this->userId,
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
            'passportUrl' => asset('storage/passports/user/' . $this->passport)
        ];
    }
}
