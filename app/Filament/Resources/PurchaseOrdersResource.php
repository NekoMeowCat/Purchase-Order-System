<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrdersResource\Pages;
use App\Filament\Resources\PurchaseOrdersResource\RelationManagers;
use App\Models\PurchaseOrders;
use App\Models\PurchaseOrderItems; // Import the model for purchase order items
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Filament\Pages\PurchaseInvoice;

class PurchaseOrdersResource extends Resource
{
    protected static ?string $model = PurchaseOrders::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('item_no')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('unit_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sub_total')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tax')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('over_all_total')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit_price'),
                Tables\Columns\TextColumn::make('total'),
                Tables\Columns\TextColumn::make('sub_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment'),
                Tables\Columns\TextColumn::make('tax')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('over_all_total')
                    ->numeric()
                    ->sortable(),
                \EightyNine\Approvals\Tables\Columns\ApprovalStatusColumn::make("approvalStatus.status"),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])

            ->actions([
                Tables\Actions\Action::make('save_to_items')
                    ->label('Save to Items')
                    ->visible(fn(PurchaseOrders $record) => $record->isApprovalCompleted() && !$record->items_saved)
                    ->requiresConfirmation()
                    ->action(function (PurchaseOrders $record) {
                        // Logic to save the purchase order ID to the purchase_order_items table
                        \App\Models\PurchaseOrderItems::create(['purchase_order_id' => $record->id]);

                        // Update the record to mark that items have been saved
                        $record->items_saved = 1; // Set items_saved to 1
                        $record->save(); // Save the updated record

                        // Display a success notification using Filament's notification system
                        \Filament\Notifications\Notification::make()
                            ->title('Success')
                            ->body('Items have been successfully saved.')
                            ->success()
                            ->send();
                    }),

                // Tables\Actions\ViewAction::make()
                //     ->visible(fn(PurchaseOrders $record) => $record->isApprovalCompleted() && $record->items_saved)
                //     ->url(fn(PurchaseOrders $record) => route('filament.admin.pages.purchase-invoice.custom', ['po_number' => $record->po_number]))
                //     ->openUrlInNewTab(),

                Tables\Actions\ViewAction::make()
                    // ->visible(fn(PurchaseOrders $record) => $record->isApprovalCompleted() && $record->items_saved)
                    ->visible(
                        fn(PurchaseOrders $record) =>
                        $record->isApprovalCompleted() && $record->items_saved && !empty($record->po_number)
                    )
                    ->url(
                        fn(PurchaseOrders $record) =>
                        route('filament.admin.pages.purchase-invoice.custom', ['po_number' => $record->po_number])
                    )
                    ->openUrlInNewTab(),





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
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CustomCreatePO::route('/create'),
            'edit' => Pages\EditPurchaseOrders::route('/{record}/edit'),
            // 'po-invoice' => PurchaseInvoice::route('purchase-invoice/{po_number}'),
        ];
    }
}
