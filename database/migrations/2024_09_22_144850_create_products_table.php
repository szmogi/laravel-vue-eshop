<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Názov produktu
            $table->text('description'); // Popis produktu
            $table->decimal('price', 10, 2); // Cena produktu
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Cudzí kľúč na kategóriu
            $table->string('sku')->unique(); // Jedinečný identifikátor produktu
            $table->string('size')->nullable(); // Pridanie stĺpca pre veľkosť
            $table->string('color')->nullable(); // Pridanie stĺpca pre farbu
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
