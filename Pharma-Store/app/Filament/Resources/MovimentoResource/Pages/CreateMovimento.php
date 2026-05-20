<?php

namespace App\Filament\Resources\MovimentoResource\Pages;

use App\Filament\Resources\MovimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Medicamento; 
use Filament\Notifications\Notification;

class CreateMovimento extends CreateRecord
{
    protected static string $resource = MovimentoResource::class;

    protected function beforeCreate(): void
    {
        $data = $this->data;
        $medicamento = Medicamento::find($data['produto_id']); 
        $quantidade = $data['quantidade'] ?? 0;
        $tipo = $data['tipo'] ?? 0;

        if (!$medicamento) {
            Notification::make()
                ->title('Produto não encontrado')
                ->body('Selecione um produto válido.')
                ->danger()
                ->send();

            $this->halt();
        }

        if ($tipo === 'saida' && $quantidade > $medicamento->quantidade) { 
            Notification::make()
                ->title('Estoque insuficiente')
                ->body("Estoque de '{$medicamento->nome}' é de apenas {$medicamento->quantidade} unidades.")
                ->danger()
                ->send();

            $this->halt();
        }
    }

    protected function afterCreate(): void
    {
        $movimento = $this->getRecord();
        $medicamento = $movimento->medicamento; 

        if ($movimento->tipo === 'entrada') {
            $medicamento->increment('quantidade'); 
        } else {
            $medicamento->decrement('quantidade'); 
        }
    }
}