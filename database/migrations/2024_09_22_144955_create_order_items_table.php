<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Cudzí kľúč na objednávku
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Cudzí kľúč na produkt
            $table->integer('quantity'); // Množstvo produktu
            $table->decimal('price', 10, 2); // Cena produktu pri objednávke
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}