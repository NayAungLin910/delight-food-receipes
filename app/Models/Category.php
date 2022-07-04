<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function receipe(){
        return $this->belongsToMany(Receipe::class)->withPivot('category_id', 'receipe_id');
    }
}
