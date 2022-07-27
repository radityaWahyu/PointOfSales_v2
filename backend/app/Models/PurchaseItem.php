<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    use \App\Http\Traits\Uuids;

    public $incrementing = false;
    protected $guarded = [];

    public function purchases()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
