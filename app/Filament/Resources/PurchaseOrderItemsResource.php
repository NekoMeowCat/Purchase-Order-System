<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderItemsResource\Pages;
use App\Filament\Resources\PurchaseOrderItemsResource\RelationManagers;
use App\Models\PurchaseOrderItems;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PurchaseOrders;



class PurchaseOrderItemsResource extends Resource
{
    protected static ?string $model = PurchaseOrderItems::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('prs_id')
                    ->numeric(),
                Forms\Components\TextInput::make('supplier_id')
                    ->numeric(),
                Forms\Components\TextInput::make('po_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('po_date')
                    ->maxLength(255),
                Forms\Components\TextInput::make('product')
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->numeric(),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('amount')
                    ->numeric(),
                Forms\Components\TextInput::make('total_amount')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('purchaseOrder.pr_number')
                    ->label('PRS #')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('po_number')
                    ->label('PO #')
                    ->searchable(),
                Tables\Columns\TextColumn::make('po_date')
                    ->label('PO Date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'danger',
                        'Approved' => 'warning',
                        'Out for Delivery' => 'info',
                        'Completed' => 'success',
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => $record->status === 'Pending'),
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
            'index' => Pages\ListPurchaseOrderItems::route('/'),
            'create' => Pages\CreatePurchaseOrderItems::route('/create'),
            'edit' => Pages\EditPO::route('/{record}/edit'),
        ];
    }
}
