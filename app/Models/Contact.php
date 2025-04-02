<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{protected $fillable = [
    'email',
    'phone',
    'githup',
    'linkedlin',
   

];

    public function portfolio(){
        return $this->belongsTo(Portfolio::class);
    }
}
