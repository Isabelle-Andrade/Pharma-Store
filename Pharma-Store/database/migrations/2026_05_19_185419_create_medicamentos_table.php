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
        Schema::create('medicamentos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nome');
            $table->string('fabricante'); // Fabricante/Fornecedor
            $table->string('principio_ativo');
            $table->string('lote');
            $table->date('data_validade');
            $table->decimal('preco', 10, 2)->default(0.00);
            $table->integer('quantidade')->default(0);
            $table->enum('tipo', ['medicamento', 'perfumaria'])->default('medicamento');
            $table->boolean('controlado')->default(false); // Tarja Preta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamentos');
    }
};