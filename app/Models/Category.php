<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Získaj všetky produkty v kategórii
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Vytvor novú kategóriu
    public static function createCategory($data)
    {
        return self::create($data);
    }

    // Aktualizuj existujúcu kategóriu
    public function updateCategory($data)
    {
        $this->update($data);
    }

    // Zmaž kategóriu
    public function deleteCategory()
    {
        $this->delete();
    }
}
