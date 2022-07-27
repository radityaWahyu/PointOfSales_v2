<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use sirajcse\UniqueIdGenerator\UniqueIdGenerator;

class Sale extends Model
{
    use HasFactory;

    use \App\Http\Traits\Uuids;

    public $incrementing = false;
    protected $guarded = [];
    protected $casted = [
        'is_pending' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $row = $model->whereDate('created_at', date('Y-m-d'))->latest()->first();
            if ($row == null) {
                $id = 'TJ-' . date('Ymd') . '0001';
            } else {
                $number = substr($row['sales_code'], -4);
                $row = (int)$number + 1;
                $id = 'TJ-' . date('Ymd') . str_pad((string)$row, 4, "0", STR_PAD_LEFT);
            }
            $model->sales_code = $id;
        });
    }

    public function sale_items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function customers()
    {
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }

    public function users()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
