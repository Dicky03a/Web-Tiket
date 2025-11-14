<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AprovedResource\Pages;
use App\Filament\Resources\AprovedResource\RelationManagers;
use App\Models\Aproved;
use App\Models\Ticket;
use App\Models\Narahubung;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AprovedResource extends Resource
{
    protected static ?string $model = Aproved::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationLabel = 'Approvals';

    protected static ?string $pluralModelLabel = 'Approvals';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('ticket_id')
                    ->label('Ticket')
                    ->relationship('ticket', 'name')
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('narahubung_id')
                    ->label('Narahubung')
                    ->relationship('narahubung', 'name')
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Select::make('approved_by')
                    ->label('Approved By')
                    ->relationship('approver', 'name')
                    ->searchable(),

                Forms\Components\DateTimePicker::make('approved_at')
                    ->label('Approved At'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket.name')
                    ->label('Ticket')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('narahubung.name')
                    ->label('Narahubung')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('notes')
                    ->limit(50),

                Tables\Columns\TextColumn::make('approver.name')
                    ->label('Approved By')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\SelectFilter::make('ticket_id')
                    ->label('Ticket')
                    ->relationship('ticket', 'name')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('narahubung_id')
                    ->label('Narahubung')
                    ->relationship('narahubung', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAproveds::route('/'),
            'create' => Pages\CreateAproved::route('/create'),
            'edit' => Pages\EditAproved::route('/{record}/edit'),
        ];
    }
}
