<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use sirajcse\UniqueIdGenerator\UniqueIdGenerator;

class Purchase extends Model
{
    use HasFactory;

    use \App\Http\Traits\Uuids;

    public $incrementing = false;
    protected $guarded = [];



    public function purchase_items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function suppliers()
    {
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }

    public function users()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
