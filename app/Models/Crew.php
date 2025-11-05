<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crew extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'zone',
        'status',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}