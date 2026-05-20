<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicamentoResource\Pages;
use App\Models\Medicamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MedicamentoResource extends Resource
{
    protected static ?string $model = Medicamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Medicamentos';

    protected static ?string $modelLabel = 'Medicamento / Produto';

    protected static ?string $pluralModelLabel = 'Medicamentos';

    protected static ?string $navigationBadgeColor = 'success';

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Produtos com estoque baixo (menos de 10 unidades)';
    }

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            // ── Identificação ───────────────────────────────────────
            Forms\Components\Section::make('Identificação do Produto')
                ->schema([
                    Forms\Components\TextInput::make('codigo')
                        ->label('Código / ID')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50)
                        ->placeholder('Ex: MED-0001'),

                    Forms\Components\TextInput::make('nome')
                        ->label('Nome do Produto')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Ex: Dipirona Monoidratada 500mg'),

                    Forms\Components\Select::make('tipo')
                        ->label('Tipo')
                        ->options([
                            'medicamento' => 'Medicamento',
                            'perfumaria'  => 'Perfumaria',
                        ])
                        ->default('medicamento')
                        ->required(),
                ])
                ->columns(2),

            // ── Fabricante & Lote ───────────────────────────────────
            Forms\Components\Section::make('Fabricante & Lote')
                ->schema([
                    Forms\Components\TextInput::make('fabricante')
                        ->label('Fabricante / Fornecedor')
                        ->required()
                        ->maxLength(100)
                        ->placeholder('Ex: Cimed'),
                ])
                ->columns(2),

            // ── Estoque & Preço ─────────────────────────────────────
            Forms\Components\Section::make('Estoque & Preço')
                ->schema([
                    Forms\Components\TextInput::make('preco')
                        ->label('Preço')
                        ->required()
                        ->numeric()
                        ->prefix('R$')
                        ->default(0.00)
                        ->minValue(0)
                        ->step(0.01),

                    Forms\Components\TextInput::make('quantidade')
                        ->label('Quantidade')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->placeholder('Ex: 100'),
                ])
                ->columns(2),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome do Produto')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'medicamento' => 'success',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'medicamento' => 'Medicamento',
                        'perfumaria'  => 'Perfumaria',
                        default       => $state,
                    }),

                Tables\Columns\TextColumn::make('fabricante')
                    ->label('Fabricante')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('preco')
                    ->label('Preço')
                    ->money('BRL')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantidade')
                    ->label('Qtd.')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state < 10  => 'warning',
                        default      => 'success',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('nome', 'asc')
            ->striped();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMedicamentos::route('/'),
            'create' => Pages\CreateMedicamento::route('/create'),
            'edit'   => Pages\EditMedicamento::route('/{record}/edit'),
        ];
    }
}