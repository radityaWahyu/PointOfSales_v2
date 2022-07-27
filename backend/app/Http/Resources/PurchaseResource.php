<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{

    private $datatype;

    public function __construct($resource, $datatype)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->datatype = $datatype;
    }
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
            'sales_code' => $this->sales_code,
            'supplier_id' => $this->supplier_id,
            'supplier' => $this->suppliers->name,
            'total_pay' => $this->total_pay,
            'paid' => $this->paid,
            'change' => $this->change,
            'type' => $this->type,
            'due_date' => $this->due_date,
            'debt_amount' => $this->debt_amount,
            'is_pending' => $this->is_pending,
            'items' => $this->datatype == 'struk' ? $this->sale_items : SaleItemResource::collection($this->sale_items),
            'created' => $this->created_at,
            'updated' => $this->updated_at
        ];
    }
}
