<?php

namespace App\Filament\Resources\PurchaseOrdersResource\Pages;

use App\Filament\Resources\PurchaseOrdersResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class Invoice extends ViewRecord
{
    protected static string $resource = PurchaseOrdersResource::class;

    protected static string $view = 'filament.pages.purchase-invoice';
}
