<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'exchange_rate' => $this->exchange_rate,
            'is_base' => $this->is_base,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

}
