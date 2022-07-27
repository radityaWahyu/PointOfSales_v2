<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    use \App\Http\Traits\Uuids;

    public $incrementing = false;
    protected $guarded = [];

    public function Unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sale_items()
    {
        return $this->hasMany(SaleItem::class, 'item_id', 'id');
    }
}
