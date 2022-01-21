<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'province_id', 'id');
    }
}
