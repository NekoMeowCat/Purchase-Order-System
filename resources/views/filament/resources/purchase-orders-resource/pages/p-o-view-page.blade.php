<x-filament-panels::page>
    @vite('resources/css/app.css')

    <main class="min-w-full border border-green-500 p-4">
        <header class="flex flex-col items-center border border-red-500 p-4 w-full">
            <article class="text-center">
                <div class="font-lato text-2xl tracking-tight">Father Saturnino Urios University</div>
                <div class="font-medium text-2xl tracking-tight">PROPERTY & MAINTENANCE OFFICE</div>
                <div class="text-lg tracking-tight">Butuan City</div>
            </article>
            <article class="text-center">
                <h1 class="uppercase underline font-medium tracking-tight text-3xl mt-4">Purchase Order</h1>
            </article>
            <article class="flex min-w-full border h-auto m-2">
                <div class="flex justify-end w-full">
                    <div class="flex">
                        <div class="">P.O No:</div>
                        <div class="w-[13rem] border-b border-black flex justify-center">{{ request()->route('po_number') }}</div>
                    </div>
                </div>
            </article>
            <article class="grid grid-cols-2 gap-2 w-full">
                <div class="flex">
                    <div class="">Supplier :</div>
                    <div class="w-[29rem] border-b border-black flex justify-center">{{ $supplierName }}</div>
                </div>
                <div class="flex justify-end w-full">
                    <div class="flex">
                        <div class="">Date:</div>
                        <div class="w-[13rem] border-b border-black flex justify-center">{{ $createdAt }}</div>
                    </div>
                </div>
            </article>
            <article class="flex justify-start w-full mt-2">
                <small class="pl-16 text-slate-500">Please furnish us with the following items subject to terms and conditions set forth below:</small>
            </article>
            <article class="grid grid-cols-2 gap-2 w-full">
                <div class="flex">
                    <div class="w-[4rem]">Terms :</div>
                    <div class="w-[30rem] border-b border-black flex justify-center">{{ request()->route('po_number') }}</div>
                </div>
                <div class="flex justify-end border">Date Required: _______________________________</div>
            </article>
        </header>

        <section class="w-full border border-yellow-500 mt-4 pr-2">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border">Quantity</th>
                        <th class="border">Description</th>
                        <th class="border">Unit Price</th>
                        <th class="border text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrders as $record)
                    <tr class="border">
                        <td class="text-center border">{{ $record['quantity'] }}</td>
                        <td class="text-center border">{{ $record['description'] }}</td>
                        <td class="text-center border">{{ number_format($record['unit_price'], 2) }}</td>
                        <td class="text-right border">{{ number_format($record['total'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>
</x-filament-panels::page>