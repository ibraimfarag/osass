<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'base_price', 'description'];

    public function priceLists()
    {
        return $this->hasMany(PriceList::class);
    }

    protected $appends = ['applicable_price'];


    public function getApplicablePriceAttribute()
    {
        if (!$this->relationLoaded('priceLists')) {
            $this->load('priceLists');
        }

        return $this->priceLists->isNotEmpty()
            ? $this->priceLists->first()->price
            : $this->base_price;
    }

    public function setApplicablePriceAttribute($value)
    {
        
        $this->attributes['applicable_price'] = $value;
    }
}
