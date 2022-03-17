<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Note;


class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
    	'name'
    ];

    public function notes()
    {
        return $this->belongsToMany('App\Models\Note');
    }
}
