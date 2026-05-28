<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'user_id', 'title', 'category', 'ingredients', 'instructions', 'prep_time', 'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
