<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    use \App\Http\Traits\Uuids;

    public $incrementing = false;
    protected $guarded = [];

    public function sale_items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
