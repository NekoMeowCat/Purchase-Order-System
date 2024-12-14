<?php

namespace App\Filament\Resources\PurchaseOrdersResource\Pages;

use App\Filament\Resources\PurchaseOrdersResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListPurchaseOrders extends ListRecords
{
    use  \EightyNine\Approvals\Traits\HasApprovalHeaderActions;

    protected static string $resource = PurchaseOrdersResource::class;
    protected static ?string $title = 'Purchase Request';
    protected static ?string $breadcrumb = "PRS";


    protected function getHeaderActions(): array
    {
        return [
            PrintAction::make()
                ->visible(fn() => auth()->user()->department && in_array(auth()->user()->department->name, ['Finance', 'VPASA', 'PMO'])),
            Actions\CreateAction::make()
        ];
    }
}
