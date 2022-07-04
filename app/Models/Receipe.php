<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slug',
        'name',
        'description',
        'image',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsToMany(Category::class)->withPivot('category_id', 'receipe_id');
    }

    public function step(){
        return $this->hasMany(Step::class);
    }

    public function favourite(){
        return $this->hasMany(Favourite::class);
    }
}
