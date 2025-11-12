<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    use HasFactory;

    protected $fillable = ['titre','description','category','image_url'];

    public function episodes(){
        return $this->hasMany(Episode::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
