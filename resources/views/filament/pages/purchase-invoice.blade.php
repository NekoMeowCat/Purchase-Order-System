<x-filament-panels::page>
    @vite('resources/css/app.css')

    <main class="min-w-full border border-green-500 p-2">
        <header class="flex flex-col items-center border border-red-500 p-2 w-full">
            <article class="text-center">
                <div class="font-lato text-2xl tracking-tight">Father Saturnino Urios University</div>
                <div class="font-medium text-2xl tracking-tight">PROPERTY & MAINTENANCE OFFICE</div>
                <div class="text-lg tracking-tight">Butuan City</div>
            </article>
            <article class="text-center">
                <h1 class="uppercase underline font-medium tracking-tight text-3xl mt-2">purchase order</h1>
            </article>
            <article class="flex min-w-full border h-auto m-2">
                <div class="flex justify-end w-full pr-10">
                    <span class="w-20"> P.O NO. :</span>
                    <span class="pl-2 font-medium"> OCT2478513</span>
                </div>
            </article>
            <article class="grid grid-cols-2 gap-2 w-full">
                <div class="border flex">
                    <span class="bg-green-100 w-20">Supplier: </span>
                    <span class="ml-2 font-medium">{{ $supplierName }}</span>
                </div>
                <div class="flex justify-end border pr-1">
                    <span class="w-20">Date:</span>
                    <span class="ml-2 font-medium">{{ date('F j, Y') }}</span>
                </div>
            </article>
            <article class="flex justify-start w-full">
                <small class="pl-16 text-slate-500">Please furnish us the following listed hereunder subject to terms and conditions set forth below:</small>
            </article>
            <article class="grid grid-cols-2 gap-2 w-full">
                <div class="border flex">
                    <span class="bg-green-100 w-20">Terms:</span>
                    <span class="ml-2 font-medium">{{ date('F j, Y') }}</span>_
                </div>
                <div class="flex justify-start border">
                    <span class="bg-green-100 w-40">Date Required: </span>
                    <span class="ml-2 font-medium">{{ $date_required }}</span>
                </div>
            </article>
        </header>

        <section class="w-full border border-yellow-500 mt-2 pr-2">
            <table class="w-full">
                <thead class="border">
                    <tr>
                        <th class="border">Quantity</th>
                        <th class="border">Description</th>
                        <th class="border">Unit Price</th>
                        <th class="flex justify-end">Amount</th>
                    </tr>
                </thead>
                <tbody class="border">
                    @foreach ($records as $record)
                    <tr class="border">
                        <td class="text-center border">{{ $record['quantity'] }}</td>
                        <td class="text-center border">{{ $record['description'] }}</td>
                        <td class="text-center border">{{ $record['unit_price'] }}</td>
                        <td class="flex justify-end">{{ $record['total'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section class="w-full border border-blue-500 mt-2 p-2 hover:bg-blue-100">
            <article class="mb-2">
                <p class="">Requested Office/Department: ___________________________________________________</p>
            </article>
            <article class="flex mb-2">
                <div class="w-full">PRS No.: _______________________________</div>
                <div class="w-full">Date: ____________________________________</div>
                <div class="w-full flex flex-col items-end pr-1">
                    <span class="">____________________________________</span>
                    <span class="text-xs text-gray-500">SUPPLIER (Signature Over Printed Name)</span>
                </div>
            </article>
            <article class="grid grid-cols-3 gap-x-2 border p-1">
                <span class="border w-full text-sm text-gray-700">Prepared By:</span>
                <span class="border w-full text-sm text-gray-700">Certified Funds Available:</span>
                <span class="border w-full text-sm text-gray-700">Approved By:</span>
                <span class="border w-full text-sm text-gray-700 text-center mt-10 uppercase font-bold">{{ $positions['PMO Director'] }}</span>
                <span class="border-b border-black text-center text-sm text-gray-700 mt-10 uppercase font-bold">{{ $positions['Comptroller'] }}</span>
                <span class="border-b border-black text-center text-sm text-gray-700 mt-10 uppercase font-bold">{{ $positions['VP for Administrative and Student Affairs'] }}</span>
                <span class="border w-full text-sm text-gray-700 text-center">Director, PMO</span>
                <span class="border w-full text-sm text-gray-700 text-center">Comptroller</span>
                <span class="border w-full text-sm text-gray-700 text-center">VP for Administrative and Student Affairs</span>
            </article>
        </section>
    </main>
</x-filament-panels::page>