<?php

// database/migrations/2025_06_04_000004_create_cupons_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuponsTable extends Migration
{
    public function up()
    {
        Schema::create('cupons', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->decimal('desconto_valor', 10, 2)->nullable();
            $table->decimal('desconto_percentual', 5, 2)->nullable();
            $table->decimal('minimo_pedido', 10, 2)->default(0);
            $table->date('validade')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cupons');
    }
}