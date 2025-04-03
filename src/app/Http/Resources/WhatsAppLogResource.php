<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WhatsAppLogResource extends JsonResource
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
            'to' => $this->meta_data['contact'],
            'status' => logStatus($this->status),
            'created_at' => getDateTime($this->created_at)
        ];
    }
}
