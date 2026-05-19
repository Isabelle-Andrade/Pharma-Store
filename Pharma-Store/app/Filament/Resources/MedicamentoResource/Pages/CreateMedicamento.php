<?php

namespace App\Filament\Resources\MedicamentoResource\Pages; 
use App\Filament\Resources\MedicamentoResource;             
use Filament\Resources\Pages\CreateRecord;
class CreateMedicamento extends CreateRecord
{
    protected static string $resource = MedicamentoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Produto cadastrado com sucesso!';
    }
}