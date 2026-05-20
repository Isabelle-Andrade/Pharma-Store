<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimento extends Model
{
    protected $fillable = [
        'produto_id',
        'tipo',
        'quantidade',
        'lote',
        'data_validade',
        'principio_ativo',
        'controlado',
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'produto_id');
    }
}