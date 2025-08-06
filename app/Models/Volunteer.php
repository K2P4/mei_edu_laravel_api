<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'image',
        'phone',
        'position',
        'batch',
        'dob',
        'team',
        'department',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
        ];
    }
}
