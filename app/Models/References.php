<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class References extends Model
{
    use HasFactory;
    public $table = 'references';
    public $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';  
    public $timestamps = false;
    protected $fillable = [
        'id',
        'url',
        'type',
        'topic',
        'created_at',
    ];
}
