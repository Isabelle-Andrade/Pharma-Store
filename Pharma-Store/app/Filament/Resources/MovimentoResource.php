<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimentoResource\Pages;
use App\Models\Movimento;
use App\Models\Medicamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MovimentoResource extends Resource
{
    protected static ?string $model = Movimento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Movimentos';

    protected static ?string $modelLabel = 'Movimento';

    protected static ?string $pluralModelLabel = 'Movimentos';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ── Seção: Produto ──────────────────────────────────────
                Forms\Components\Section::make('Produto')
                    ->schema([
                        Forms\Components\Select::make('produto_id')
                            ->label('Medicamento / Produto')
                            ->relationship('medicamento', 'nome')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('quantidade')
                            ->label('Quantidade')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('Ex: 10'),

                        Forms\Components\TextInput::make('lote')
                            ->label('Lote de Fabricação')
                            ->maxLength(100)
                            ->placeholder('Ex: LOT-2024-0892'),

                        Forms\Components\DatePicker::make('data_validade')
                            ->label('Data de Validade')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->columns(2),

                // ── Seção: Especificidades ──────────────────────────────
                Forms\Components\Section::make('Especificidades do Produto')
                    ->schema([
                        Forms\Components\TextInput::make('principio_ativo')
                            ->label('Princípio Ativo')
                            ->maxLength(255)
                            ->placeholder('Ex: Dipirona Monoidratada'),

                        Forms\Components\Radio::make('controlado')
                            ->label('Controlado / Tarja Preta')
                            ->options([
                                1 => 'Não',
                                0 => 'Sim',
                            ])
                            ->default(0)
                            ->inline(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('medicamento.nome')
                    ->label('Produto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantidade')
                    ->label('Qtd.')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('lote')
                    ->label('Lote')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('data_validade')
                    ->label('Validade')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('principio_ativo')
                    ->label('Princípio Ativo')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('controlado')
                    ->label('Controlado')
                    ->boolean()
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),
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
            ->defaultSort('created_at', 'desc')
            ->striped();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMovimentos::route('/'),
            'create' => Pages\CreateMovimento::route('/create'),
            'edit'   => Pages\EditMovimento::route('/{record}/edit'),
        ];
    }
}