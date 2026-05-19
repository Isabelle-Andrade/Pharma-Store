<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->string('codigo', 50)->unique()->after('id');
            $table->string('nome', 255)->after('codigo');
            $table->enum('tipo', ['medicamento'])->default('medicamento')->after('nome');
            $table->string('fabricante', 255)->after('tipo');
            $table->string('principio_ativo', 255)->nullable()->after('fabricante');
            $table->boolean('controlado')->default(false)->after('principio_ativo');
            $table->string('lote', 100)->after('controlado');
            $table->date('data_validade')->after('lote');
            $table->decimal('preco', 10, 2)->default(0.00)->after('data_validade');
            $table->integer('quantidade')->default(0)->after('preco');
        });
    }

    public function down(): void
    {
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->dropColumn([
                'codigo', 'nome', 'tipo', 'fabricante',
                'principio_ativo', 'controlado', 'lote',
                'data_validade', 'preco', 'quantidade',
            ]);
        });
    }
};