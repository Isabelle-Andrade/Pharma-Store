<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    
    {
        Schema::create('movimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('medicamentos')->cascadeOnDelete();
            $table->enum('tipo', ['entrada', 'saida'])->default('entrada');
            $table->integer('quantidade');
            $table->string('lote')->nullable();
            $table->date('data_validade')->nullable();
            $table->string('principio_ativo')->nullable();
            $table->boolean('controlado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentos');
    }
};