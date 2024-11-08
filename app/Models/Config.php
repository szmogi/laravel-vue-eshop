<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = ['key', 'value'];

    // Ak chceš priamo uložiť hodnotu ako JSON
    protected $casts = [
        'value' => 'array', // alebo 'json' pre JSON
    ];

    // Ak chceš ukladať hodnotu ako serializovaný objekt
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = serialize($value);
    }

    public function getValueAttribute($value)
    {
        return unserialize($value);
    }
}
