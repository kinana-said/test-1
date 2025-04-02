<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'section_type',
        'content',
        'portfolio_id',

    ];
    public function portfolio(){
        return $this->belongsTo(Portfolio::class);
    }
}
