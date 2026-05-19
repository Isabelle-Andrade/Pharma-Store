<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicamentoResource\Pages;
use App\Models\Medicamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class MedicamentoResource extends Resource
{
    protected static ?string $model = Medicamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationLabel = 'Medicamentos';

    protected static ?string $modelLabel = 'Medicamento / Produto';

    protected static ?string $pluralModelLabel = 'Medicamentos';

    protected static ?string $navigationGroup = 'Estoque';

    protected static ?int $navigationSort = 1;

    // Cor primária da Pharma-Store: Verde #16A34A
    protected static ?string $navigationBadgeColor = 'success';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('quantidade', '<', 10)->count() ?: null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Produtos com estoque baixo (menos de 10 unidades)';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ── Seção: Identificação ────────────────────────────────
                Forms\Components\Section::make('Identificação do Produto')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\TextInput::make('codigo')
                            ->label('Código / ID')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('EX: MED-0001'),

                        Forms\Components\TextInput::make('nome')
                            ->label('Nome do Produto')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Dipirona Monoidratada 500mg'),

                

                        Forms\Components\TextInput::make('principio_ativo')
                            ->label('Princípio Ativo')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: EMS Sigma Pharma'),
                    ])
                    ->columns(2),

                // ── Seção: Dados Farmacêuticos ──────────────────────────
                Forms\Components\Section::make('Dados Farmacêuticos')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->visible(fn (Forms\Get $get) => $get('tipo') === 'medicamento')
                    ->schema([
                        Forms\Components\TextInput::make('principio_ativo')
                            ->label('Princípio Ativo')
                            ->maxLength(255)
                            ->placeholder('Ex: Dipirona Monoidratada'),

                        Forms\Components\Toggle::make('controlado')
                            ->label('Medicamento Controlado / Tarja Preta')
                            ->helperText('Ative para medicamentos que exigem receita controlada.')
                            ->onColor('danger')
                            ->offColor('success')
                            ->inline(false),
                    ])
                    ->columns(2),

              
                // ── Seção: Lote e Validade ──────────────────────────────
                Forms\Components\Section::make('Lote e Validade')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Forms\Components\TextInput::make('lote')
                            ->label('Lote de Fabricação')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Ex: LOT-2024-0892'),

                        Forms\Components\DatePicker::make('data_validade')
                            ->label('Data de Validade')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->minDate(now()),
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

                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->colors([
                        'success' => 'medicamento',
                        'info'    => 'perfumaria',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'medicamento' => 'Medicamento',
                        default       => $state,
                    }),

                Tables\Columns\IconColumn::make('controlado')
                    ->label('Controlado')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),
                    
                Forms\Components\Select::make('controlado')
                ->label('Medicamento Controlado / Tarja Preta')
                ->options([
                    1 => 'Sim',
                    0 => 'Não',
                ])
                ->default(0)
                ->required(),

                Tables\Columns\TextColumn::make('fabricante')
                    ->label('Fabricante')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('principio_ativo')
                    ->label('Princípio Ativo')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('lote')
                    ->label('Lote')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('data_validade')
                    ->label('Validade')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->data_validade->isPast() ? 'danger' : 'success'),

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
                        $state === 0  => 'danger',
                        $state < 10   => 'warning',
                        default       => 'success',
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
            ->filters([
                SelectFilter::make('tipo')
                    ->label('Tipo de Produto')
                    ->options([
                        'medicamento' => '💊 Medicamento',
                        'perfumaria'  => '🧴 Perfumaria',
                    ]),

                TernaryFilter::make('controlado')
                    ->label('Controlado / Tarja Preta')
                    ->trueLabel('Somente Controlados')
                    ->falseLabel('Não Controlados'),

                Tables\Filters\Filter::make('vencidos')
                    ->label('Vencidos')
                    ->query(fn ($query) => $query->where('data_validade', '<', now())),

                Tables\Filters\Filter::make('estoque_baixo')
                    ->label('Estoque Baixo (< 10)')
                    ->query(fn ($query) => $query->where('quantidade', '<', 10)),

                Tables\Filters\Filter::make('sem_estoque')
                    ->label('Sem Estoque')
                    ->query(fn ($query) => $query->where('quantidade', 0)),
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