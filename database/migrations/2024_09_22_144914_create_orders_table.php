<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable(); // Cudzí kľúč na užívateľa (ak máš tabuľku users)
            $table->decimal('total', 10, 2); // Celková suma objednávky
            $table->string('status'); // Stav objednávky (napr. 'pending', 'completed')
            $table->json('data')->nullable(); // Pridanie stĺpca pre serializované údaje
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
