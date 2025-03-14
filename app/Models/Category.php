<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // protected $table = 'category';
    use HasFactory; 
    protected $fillable = ['name','slug'];

    public function sub_categories(){
        return $this->hasMany(Sub_category::class);
    }
}
