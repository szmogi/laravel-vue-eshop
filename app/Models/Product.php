<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'category_id', 'sku', 'size_id', 'color_id', 'master_id', 'stock'];

    /**
     * Scope to filter only master products (not variants)
     */
    public function scopeOnlyMaster($query)
    {
        // Assuming master products have `master_id` as `null` or equal to their own `id`
        return $query->where(function ($q) {
            $q->whereNull('master_id')
                ->orWhereColumn('id', 'master_id');
        });
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Získaj kategóriu produktu
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Získaj farbu produktu
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // Získaj farbu produktu
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    // Vytvor nový produkt
    public static function createProduct($data)
    {
        return self::create($data);
    }

    // Aktualizuj existujúci produkt
    public function updateProduct($data)
    {
        $this->update($data);
    }

    // Zmaž produkt
    public function deleteProduct()
    {
        $this->delete();
    }

    public function masterProduct()
    {
        return $this->belongsTo(Product::class, 'master_id');
    }

    public function variants()
    {
        return $this->hasMany(Product::class, 'master_id', 'master_id');
    }
}
