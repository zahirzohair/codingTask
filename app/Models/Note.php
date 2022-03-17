<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;


class Note extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
    	'content',
    	'due_date',
    	'is_done'
    ];



    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }
}
