<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'start_date'];

    public function images()
    {
        return $this->hasMany(EventImage::class);
    }
}
