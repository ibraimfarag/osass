<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'country_code',
        'currency_code',
        'price',
        'start_date',
        'end_date',
        'priority',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
}