<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    // Získaj objednávku
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Získaj produkt
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Vytvor nový podrobnosť objednávky
    public static function createOrderItem($data)
    {
        return self::create($data);
    }

    // Aktualizuj existujúci podrobnosť objednávky
    public function updateOrderItem($data)
    {
        $this->update($data);
    }

    // Zmaž podrobnosť objednávky
    public function deleteOrderItem()
    {
        $this->delete();
    }
}
