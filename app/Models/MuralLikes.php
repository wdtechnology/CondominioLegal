<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuralLikes extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = 'murallikes';
}
