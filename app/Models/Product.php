<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name','slug','price','stock','status','sub_categorie_id'];


    public function sub_category(){
        return $this->belongsTo(Sub_category::class);
    }

    public function product_images(){
        return $this->hasMany(Product_images::class);
    }
}
