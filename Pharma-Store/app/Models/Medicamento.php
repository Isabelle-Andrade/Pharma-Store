<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    protected $fillable = [
        'codigo',
        'nome',
        'fabricante',
        'principio_ativo',
        'lote',
        'data_validade',
        'preco',
        'quantidade',
        'tipo',
        'controlado',
    ];

    protected $casts = [
        'preco'         => 'decimal:2',
        'data_validade' => 'date',
        'controlado'    => 'boolean',
    ];

    /**
     * Verifica se o medicamento está vencido.
     */
    public function isVencido(): bool
    {
        return $this->data_validade->isPast();
    }

    /**
     * Verifica se o estoque está baixo (menos de 10 unidades).
     */
    public function estoqueBaixo(): bool
    {
        return $this->quantidade < 10;
    }
}