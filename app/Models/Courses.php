<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;
    public $table = 'courses';
    public $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';  
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'created_at',
    ];
}
