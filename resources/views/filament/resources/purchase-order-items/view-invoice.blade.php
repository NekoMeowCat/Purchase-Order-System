<x-filament::page>
    @vite('resources/css/app.css')
    <main class="min-h-screen w-full bg-white rounded-sm shadow-lg p-6">
        <div class="flex justify-end w-full">
            <span class="">
                <x-filament::badge
                    :icon="match($record->status) {
                        'Pending' => 'heroicon-s-exclamation-triangle',
                        'Approved' => 'heroicon-s-check-circle',
                        'Out for Delivery' => 'heroicon-s-truck',
                        'Completed' => 'heroicon-s-shield-check',
                        default => 'heroicon-s-question-mark-circle' 
                    }"
                    icon-position="after"
                    :color="match($record->status) {
                    'Pending' => 'danger',
                    'Approved' => 'warning',
                    'Out for Delivery' => 'info',
                    'Completed' => 'success',
                    default => 'secondary' 
                }">
                    {{ $record->status }}
                </x-filament::badge>
            </span>
        </div>
        <header class="block w-full my-5">
            <section class="mb-5">
                <div class="capitalize flex justify-center font-medium text-lg font-bona">father saturnino urios university</div>
                <div class="uppercase flex justify-center font-bold text-xl font-bona">property & maintenance office</div>
                <span class="capitalize flex justify-center font-bona">butuan city</span>
            </section>
            <section class="flex justify-center">
                <span class="uppercase text-2xl font-bona font-bold underline">purchase order</span>
            </section>
            <section class="flex justify-end">
                <span class="w-[4rem]">P.O #: </span>
                <span class="w-[10rem] border-b border-slate-400">{{ $record->po_number }}</span>
            </section>
            <section class="flex justify-between">
                <div class="w-[50%] flex">
                    <span class="w-[8rem]">To Supplier:</span>
                    <span class="border-b border-slate-400 w-full">{{ $record->supplier->name ?? ' ' }}</span>
                </div>
                <div class="flex justify-end">
                    <span class="w-[3.5rem]">Date:</span>
                    <span class="w-[10rem] border-b border-slate-400">{{ \Carbon\Carbon::parse($record->po_date)->format('F j, Y') }}</span>
                </div>
            </section>
            <small class="w-full mx-[6.5rem]">please furnish us the following listed hereunder subject to terms and conditions set forth below:</small>
            <section class="flex justify-between">
                <div class="w-[50%] flex">
                    <span class="w-[8rem]">Terms:</span>
                    <span class="border-b border-slate-400 w-full"></span>
                </div>
                <div class="flex justify-end">
                    <span class="w-[8rem]">Date Required:</span>
                    <span class="w-[10rem] border-b border-slate-400">{{ $record->date_required ? \Carbon\Carbon::parse($record->date_required)->format('F j, Y') : ' ' }}</span>
                </div>
            </section>
        </header>
        <section class="">
            @if(empty($record->po_number))
            <div class="w-full text-center p-4 bg-yellow-100 text-yellow-800 border border-yellow-200 rounded">
                Purchase Order is Pending
            </div>
            @else
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 w-[8rem]">Quantity</th>
                        <th class="border p-2">Description</th>
                        <th class="border p-2 w-[8rem]">Price</th>
                        <th class="border p-2 w-[8rem] text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->related_items as $item)
                    <tr>
                        <td class="text-center border p-2">{{ $item->quantity }}</td>
                        <td class="text-center border p-2">{{ $item->description }}</td>
                        <td class="text-center border p-2">{{ $item->price }}</td>
                        <td class="text-end border p-2">{{ $item->amount }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right border font-bold">Grand Total:</td>
                        <td class="text-right border font-bold px-2">{{ number_format($record->related_items->sum('amount'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            @endif
        </section>
        <section class="w-full flex justify-end my-2">
            <span class="w-[6rem]">Conforme:</span>
            <span class="w-[15rem] border-b-2"></span>
        </section>
        <section class="flex space-x-2">
            <span class="">Requested Office/Department:</span>
            <span class="w-[15rem] border-b-2"> {{ $record->purchaseOrder->department ?? 'N/A' }}</span>
        </section>
        <section class="flex justify-evenly space-x-4">
            <div class="w-full flex">
                <span class="w-[5rem]">PRS No:</span>
                <span class="border-b-2 w-full text-sm">{{ $prNumber }}</span>
            </div>
            <div class="flex items-center">
                <span class="w-[3rem]">Date:</span>
                <span class="text-sm text-gray-700 w-[10rem] border-b-2">
                    {{ \Carbon\Carbon::parse($prsDate)->format('F j, Y') }}
                </span>
            </div>
            <div class="w-full flex">
                <span class="border-b-2 w-full"></span>
            </div>
        </section>
        <small class="flex justify-end">
            <span class="mx-[4rem]">SUPPLIER (Signature over Printed Name)</span>
        </small>
        <section class="w-full flex justify-evenly space-x-4 mt-8">
            <div class="block w-full">
                <div class="text-center">
                    @if($directorPmoSignature)
                    <img src="{{ $directorPmoSignature }}" alt="Director, PMO Signature" class="mx-auto h-16">
                    @else
                    <div class="text-sm text-gray-700">Signature not available</div>
                    @endif
                    <span class="text-sm text-gray-700">{{ $directorPmoName}}</span>
                </div>
                <div class="text-center border-t-2">Director, PMO</div>
            </div>
            <div class="block w-full">
                <div class="text-center">
                    @if($comptrollerSignature)
                    <img src="{{ $comptrollerSignature }}" alt="Comptroller Signature" class="mx-auto h-16">
                    @else
                    <div class="text-sm text-gray-700">Signature not available</div>
                    @endif
                    <span class="text-sm text-gray-700">{{ $comptrollerName }}</span>
                </div>
                <div class="text-center border-t-2">Comptroller</div>
            </div>
            <div class="block w-full">
                <div class="text-center ">
                    @if($vpSignature)
                    <img src="{{ $vpSignature }}" alt="Vice President Signature" class="mx-auto h-16 ">
                    @else
                    <div class="text-sm text-gray-700 mb-1">Signature not available</div>
                    @endif
                    <span class="text-sm text-gray-700 ">{{ $vpName }}</span>
                </div>
                <div class="text-center border-t-2">VP for Administrative and Student Affairs</div>
            </div>
        </section>
    </main>
</x-filament::page>