<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'barcode' => $this->barcode,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'category_name' => empty($this->category_id) ? null : $this->category->name,
            'unit_id' => $this->unit_id,
            'unit_name' => empty($this->unit_id) ? null : $this->unit->name,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
            'created' => $this->created_at,
            'updated' => $this->updated_at
        ];
    }
}
