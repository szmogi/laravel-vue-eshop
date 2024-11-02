<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total', 'status', 'data'];

    // Získaj podrobnosti objednávky
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Vytvor novú objednávku
    public static function createOrder($data)
    {
        return self::create($data);
    }

    // Aktualizuj existujúcu objednávku
    public function updateOrder($data)
    {
        $this->update($data);
    }

    // Zmaž objednávku
    public function deleteOrder()
    {
        $this->delete();
    }


    /**
     * Prístupník pre formátovanie created_at.
     */
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d.m.Y H:i'); // Nastav formát podľa potreby
    }

    /**
     * Prístupník pre formátovanie updated_at, ak je potrebné.
     */
    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d.m.Y H:i'); // Nastav formát podľa potreby
    }
}
