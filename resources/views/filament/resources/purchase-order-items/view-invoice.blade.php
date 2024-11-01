<x-filament::page>
    <main class="min-h-screen w-full bg-white rounded-sm shadow-lg p-6">
        <header class="block w-full my-5">
            <section class="mb-5">
                <div class="capitalize flex justify-center font-medium text-lg">father saturnino urios university</div>
                <div class="uppercase flex justify-center font-bold text-xl">property & maintenance office</div>
                <span class="capitalize flex justify-center">butuan city</span>
            </section>
            <section class="flex justify-center">
                <span class="uppercase text-2xl font-bold underline">purchase order</span>
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
                        <td class="text-right border font-bold px-2">{{ $record->related_items->sum('amount') }}</td>
                    </tr>
                </tfoot>
            </table>
            @endif
        </section>
    </main>
</x-filament::page>