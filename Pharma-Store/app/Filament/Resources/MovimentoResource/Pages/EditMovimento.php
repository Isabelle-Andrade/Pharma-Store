<?php

namespace App\Filament\Resources\MovimentoResource\Pages;

use App\Filament\Resources\MovimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Medicamento;
use Filament\Notifications\Notification;

class EditMovimento extends EditRecord
{
    protected static string $resource = MovimentoResource::class;

    // Guarda os valores originais antes de editar
    protected array $dadosAntigos = [];

    protected function beforeFill(): void
    {
        $movimento = $this->getRecord();
        $this->dadosAntigos = [
            'tipo'       => $movimento->tipo,
            'quantidade' => $movimento->quantidade,
            'produto_id' => $movimento->produto_id,
        ];
    }

    protected function beforeSave(): void
    {
        $data = $this->data;
        $medicamento = Medicamento::find($data['produto_id']);
        $novaQtd = (int) $data['quantidade'];
        $novoTipo = $data['tipo'];

        if (!$medicamento) {
            Notification::make()
                ->title('Produto não encontrado')
                ->body('Selecione um produto válido.')
                ->danger()
                ->send();

            $this->halt();
        }


        // Verifica se tem estoque para o novo movimento
        if ($novoTipo === 'saida' && $novaQtd > $medicamento->quantidade) {
            // Reverte a reversão para não deixar o estoque inconsistente
            if ($this->dadosAntigos['tipo'] === 'entrada') {
                $medicamento->increment('quantidade', $this->dadosAntigos['quantidade']);
            } else {
                $medicamento->decrement('quantidade', $this->dadosAntigos['quantidade']);
            }

            Notification::make()
                ->title('Estoque insuficiente')
                ->body("Estoque de '{$medicamento->nome}' é de apenas {$medicamento->quantidade} unidades.")
                ->danger()
                ->send();

            $this->halt();
        }

        // Aplica o novo movimento
        if ($novoTipo === 'entrada') {
            $medicamento->increment('quantidade', $novaQtd);
        } else {
            $medicamento->decrement('quantidade', $novaQtd);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}