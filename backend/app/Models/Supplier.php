<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    use \App\Http\Traits\Uuids;

    public $incrementing = false;
    protected $guarded = [];
}
