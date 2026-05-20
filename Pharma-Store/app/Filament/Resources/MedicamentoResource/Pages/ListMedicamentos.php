<?php

namespace App\Filament\Resources\MedicamentoResource\Pages; 

use App\Filament\Resources\MedicamentoResource;         
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;               
use Illuminate\Database\Eloquent\Builder;                  

class ListMedicamentos extends ListRecords
{
    protected static string $resource = MedicamentoResource::class; 

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo Medicamento')  
                ->icon('heroicon-o-plus'),
        ];
    }

   
}