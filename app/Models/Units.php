<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    use HasFactory;
    public $table = 'units';
    public $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';  
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'subject',
        'created_at',
    ];
}
