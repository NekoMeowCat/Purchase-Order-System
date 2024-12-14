<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PurchaseOrders;
use Filament\Resources\Resource;
use App\Models\PurchaseOrderItems;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PurchaseOrderItemsResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Filament\Resources\PurchaseOrderItemsResource\RelationManagers;
use EightyNine\Approvals\Tables\Actions\ApprovalActions;

class PurchaseOrderItemsResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = PurchaseOrderItems::class;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
        ];
    }

    protected static ?string $navigationIcon = 'heroicon-s-arrow-path-rounded-square';

    protected static ?string $navigationLabel = 'Purchase Orders';

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
                Tables\Columns\TextColumn::make('purchaseOrder.department')
                    ->label('Department'),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('po_number')
                    ->label('PO #')
                    ->searchable(),
                Tables\Columns\TextColumn::make('po_date')
                    ->label('PO Date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'danger',
                        'Approved' => 'warning',
                        'Out for Delivery' => 'info',
                        'Completed' => 'success',
                    }),
                // \EightyNine\Approvals\Tables\Columns\ApprovalStatusColumn::make("approvalStatus.status"),
            ])
            ->filters([
                //
            ])
            ->actions([
                ...\EightyNine\Approvals\Tables\Actions\ApprovalActions::make(
                    Tables\Actions\Action::make("Done")
                        ->hidden(),
                    [
                        Tables\Actions\EditAction::make()
                            ->hidden(),
                        Tables\Actions\ViewAction::make()
                            ->hidden()
                    ]
                ),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    // ->visible(fn($record) => $record->status == 'Pending'),
                    Tables\Actions\ViewAction::make()
                        ->label('View Invoice')
                        ->icon('heroicon-s-receipt-refund')
                        ->color('success')
                        ->visible(fn($record) => $record->status !== 'Pending')
                        ->openUrlInNewTab(),
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
            'index' => Pages\ListPurchaseOrderItems::route('/'),
            'create' => Pages\CreatePurchaseOrderItems::route('/create'),
            'edit' => Pages\EditPO::route('/{record}/edit'),
            'view' => Pages\InvoiceView::route('/{record}'),
        ];
    }
}
