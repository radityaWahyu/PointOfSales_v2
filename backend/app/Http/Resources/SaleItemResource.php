<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
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
            'id' => $this->id,
            'sale_id' => empty($this->sale_id) ? null : $this->sale_id,
            'item_id' => $this->item_id,
            'unit' => $this->items->Unit->name,
            'name' => $this->items->name,
            'amount' => $this->amount,
            'selling_price' => $this->price,
            'subtotal' => $this->subtotal
        ];
    }
}
