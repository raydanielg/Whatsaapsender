<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetEmailLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uid' => $this->uid,
            'email' => $this->meta_data['contact'],
            'content' => $this->message,
            'status' => logStatus($this->status),
            'updated_at' => getDateTime($this->updated_at)
        ];
    }
}
