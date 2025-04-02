<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'description',
        'title',
        'user_id',

    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function works(){
        return $this->hasMany( Work::class);
    }
    public function sections(){
        return $this->hasMany( Section::class);
    }
}
