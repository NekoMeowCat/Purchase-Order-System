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

class PurchaseOrdersResource extends Resource
{
    protected static ?string $model = PurchaseOrders::class;

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
                Tables\Columns\TextColumn::make('unit_no')
                    ->label('Unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pr_number')
                    ->label('Purchase Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('budget_code')
                    ->label('Budget Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prs_date')
                    ->label('Purchase Date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prs_date')
                    ->label('Purchase Date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prs_date')
                    ->label('Purchase Date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('total'),
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
                    Tables\Actions\Action::make('upload_files')
                        ->label('Add Attachments')
                        ->icon('heroicon-s-paper-clip')
                        ->color('warning')
                        ->visible(
                            fn(PurchaseOrders $record) => (auth()->user()->position === 'Dean' || auth()->user()->position === 'Admin') &&
                                is_null($record->attachments)
                        )
                        ->form([
                            Forms\Components\FileUpload::make('attachments')
                                ->label('Attachments')
                                ->multiple()
                                ->directory('purchase-orders')
                                ->preserveFilenames()
                                ->acceptedFileTypes(['application/pdf', 'image/*'])
                                ->maxSize(5120)
                        ])
                        ->action(function (PurchaseOrders $record, array $data): void {
                            $record->attachments = $data['attachments'];
                            $record->save();

                            Notification::make()
                                ->title('Files uploaded successfully')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('save_to_items')
                        ->label('Save to Items')
                        ->icon('heroicon-s-bookmark')
                        ->color('info')
                        ->visible(
                            fn(PurchaseOrders $record) =>
                            $record->isApprovalCompleted() &&
                                !$record->items_saved &&
                                !\App\Models\PurchaseOrders::where('pr_number', $record->pr_number)
                                    ->where('items_saved', 1)
                                    ->exists()
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

                    // Tables\Actions\ViewAction::make()
                    //     ->label('View Invoice')
                    //     ->icon('heroicon-s-document-duplicate')
                    //     ->color('success')
                    //     ->visible(
                    //         fn(PurchaseOrders $record) =>
                    //         $record->isApprovalCompleted() && $record->items_saved && !empty($record->po_number)
                    //     )
                    //     ->url(
                    //         fn(PurchaseOrders $record) =>
                    //         route('filament.admin.pages.purchase-invoice.custom', ['po_number' => $record->po_number])
                    //     )
                    //     ->openUrlInNewTab(),
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
