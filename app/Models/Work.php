<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{protected $fillable = [

    'content',
    'title',
    'image',
    'portfolio_id',
    'link',

];
    public function portfolio(){
        return $this->belongsTo(Portfolio::class);
    }
    public function getImageUrlAttribute()
    {
        if($this->image){
            $basePath="storage";
            return url('$basePath/$this->image');
        }return null;
    }
}
