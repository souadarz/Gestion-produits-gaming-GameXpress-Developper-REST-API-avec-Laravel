<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sub_category extends Model
{
    protected $fillable = ['name','slug'];


    public function category(){
        return $this->belongsTo(Category::class);
    }
}
