<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\References;

class Topics extends Model
{
    use HasFactory;
    public $table = 'topics';
    public $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';  
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'unit',
        'created_at',
    ];

    public function references()
    {
        return $this->hasMany(References::class, 'topic', 'id');
    }
}
