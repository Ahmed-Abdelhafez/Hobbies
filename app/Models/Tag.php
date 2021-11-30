<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    public function hobbies(){
        return $this->belongsToMany('App\Models\Hobby');
    }


    use HasFactory;

    protected $fillable = [
        'name',
        'style',
    ];
}
