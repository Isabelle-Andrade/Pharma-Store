<?php

namespace App\Filament\Resources\MedicamentoResource\Pages; 
use App\Filament\Resources\MedicamentoResource;             
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedicamento extends EditRecord
{
    protected static string $resource = MedicamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Produto atualizado com sucesso!';
    }
}