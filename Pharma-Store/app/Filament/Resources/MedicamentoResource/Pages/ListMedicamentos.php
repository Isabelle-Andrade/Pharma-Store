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

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos')
                ->icon('heroicon-o-squares-2x2'),

            'medicamentos' => Tab::make('Medicamentos')
                ->icon('heroicon-o-beaker')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tipo', 'medicamento')),

        
            'controlados' => Tab::make('Controlados')
                ->icon('heroicon-o-exclamation-triangle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('controlado', true))
                ->badge(fn () => \App\Models\Medicamento::where('controlado', true)->count()),

            'estoque_baixo' => Tab::make('Estoque Baixo')
                ->icon('heroicon-o-arrow-trending-down')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quantidade', '<', 10))
                ->badge(fn () => \App\Models\Medicamento::where('quantidade', '<', 10)->count())
                ->badgeColor('warning'),

            'vencidos' => Tab::make('Vencidos')
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('data_validade', '<', now()))
                ->badge(fn () => \App\Models\Medicamento::where('data_validade', '<', now())->count())
                ->badgeColor('danger'),
        ];
    }
}