<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrdersResource\Pages;
use App\Filament\Resources\PurchaseOrdersResource\RelationManagers;
use App\Models\PurchaseOrders;
// use App\Models\PurchaseOrderItems; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use App\Models\PurchaseOrder;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;


class PurchaseOrdersResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = PurchaseOrders::class;

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

    protected static ?string $navigationLabel = 'Purchase Request';

    protected static ?string $breadcrumb = 'Purchase Request';

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
                Tables\Columns\TextColumn::make('department')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prs_date')
                    ->label('Date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pr_number')
                    ->label('PR #')
                    ->searchable(),
                Tables\Columns\TextColumn::make('budget_code')
                    ->label('Budget Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit_no')
                    ->label('Unit'),
                Tables\Columns\TextColumn::make('total')
                    ->searchable(),
                \EightyNine\Approvals\Tables\Columns\ApprovalStatusColumn::make("approvalStatus.status"),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordUrl(function ($record) {
                return Pages\POViewPage::getUrl([$record->pr_number]); // Ensure you're passing the po_number
            })
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
                    Tables\Actions\Action::make('save_to_items')
                        ->label('Save to Items')
                        ->icon('heroicon-s-bookmark')
                        ->color('info')
                        ->visible(
                            fn(PurchaseOrders $record) =>
                            auth()->user()->hasRole('PMO') &&
                                $record->isApprovalCompleted() &&
                                $record->items_saved == 0
                        )
                        ->requiresConfirmation()
                        ->action(function (PurchaseOrders $record) {
                            \App\Models\PurchaseOrderItems::create(['prs_id' => $record->id]);

                            $record->items_saved = 1;
                            $record->save();

                            \Filament\Notifications\Notification::make()
                                ->title('Success')
                                ->body('Items have been successfully saved.')
                                ->success()
                                ->send();
                        }),
                ]),

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
            'view' => Pages\POViewPage::route('/{pr_number}'),
            // 'po-invoice' => PurchaseInvoice::route('purchase-invoice/{po_number}'),
        ];
    }
}
