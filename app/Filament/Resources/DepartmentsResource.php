<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Departments;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use App\Filament\Resources\DepartmentsResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Filament\Resources\DepartmentsResource\RelationManagers;


class DepartmentsResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Departments::class;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            // 'delete',
            // 'delete_any',
            // 'publish'
        ];
    }

    protected static ?string $navigationIcon = 'heroicon-s-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('user_in_charge_id')
                            ->label('Officer In Charge')
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('userInCharge.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make()
                        ->color('danger'),
                ])
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartments::route('/create'),
            'edit' => Pages\EditDepartments::route('/{record}/edit'),
        ];
    }
}
