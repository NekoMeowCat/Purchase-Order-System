<x-filament-panels::page>
    @vite('resources/css/app.css')


    <div x-data="{ 
            printTable() { 
                const printContents = this.$refs.printableTable.innerHTML;
                const originalContents = document.body.innerHTML;

                // Replace the body content with the printable content
                document.body.innerHTML = printContents;

                // Open the print dialog
                window.print();

                // Restore the original contents and reload the page immediately
                document.body.innerHTML = originalContents;
                location.reload();
            } 
        }">
        <div class="flex justify-end mb-4">
            <button @click="printTable" class="relative inline-flex items-center justify-center p-4 px-6 py-2 overflow-hidden font-medium text-indigo-600 transition duration-300 ease-out border-2 border-teal-500 rounded-md shadow-md group">
                <span class="absolute inset-0 flex items-center justify-center w-full h-full text-white duration-300 -translate-x-full bg-teal-700 group-hover:translate-x-0 ease">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                    </svg>
                </span>
                <span class="absolute flex items-center justify-center w-full h-full text-whie transition-all duration-300 transform group-hover:translate-x-full ease text-black">Print</span>
                <span class="relative invisible text-white">Print</span>
            </button>
        </div>


        <main x-ref="printableTable" class="min-w-full bg-white p-4 rounded-sm shadow-lg mx-auto">
            <header class="flex flex-col p-4 w-full">
                <section class="flex flex-col items-center w-full mb-4 text-center">
                    <h1 class="capitalize font-medium text-xl">Fr. Saturnino Urios University</h1>
                    <p class="capitalize">Butuan City</p>
                </section>

                <section class="flex items-end justify-end w-full">
                    <label for="pr-number" class="mr-2 md:w-[5rem]">P.R #:</label>
                    <div id="pr-number" class="md:w-[10rem] border-b-2 border-gray-400 flex justify-center">
                        {{ request()->route('pr_number') }}
                    </div>
                </section>
                <section class="flex flex-col items-center w-full mb-4">
                    <span class="uppercase font-medium text-xl">purchase requisition slip</span>
                    <small class="">(amount more than Php 700.00)</small>
                </section>

                <section class="flex items-center justify-between w-full">
                    <div class="flex items-start">
                        <label for="department" class="font-medium w-[7rem]">Department:</label>
                        <span class="ml-2 w-[15rem] border-b-2 border-gray-400">{{ $purchaseOrders->first()->department }}</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <label for="prs_date" class="font-medium md:w-[5rem]">PRS Date:</label>
                        <span class="ml-2 md:w-[10rem] border-b-2 border-gray-400 flex justify-center">
                            {{ \Carbon\Carbon::parse($purchaseOrders->first()->prs_date)->format('F j, Y') ?? 'N/A' }}
                        </span>
                    </div>
                </section>

                <section class="flex flex-col mb-2">
                    <div class="flex items-start">
                        <label for="budget_code" class="font-medium w-[7rem]">Budget Code:</label>
                        <span class="ml-2 w-[15rem] border-b-2 border-gray-400">{{ $purchaseOrders->first()->budget_code ?? ' ' }}</span>
                    </div>
                    <div class="flex items-start">
                        <label for="purpose" class="font-medium w-[7rem]">Purpose:</label>
                        <span class="ml-2 w-[15rem] border-b-2 border-gray-400">{{ $purchaseOrders->first()->purpose ?? ' ' }}</span>
                    </div>
                    <div class="flex items-start">
                        <label for="payee" class="font-medium w-[7rem]">Payee:</label>
                        <span class="ml-2 w-[15rem] border-b-2 border-gray-400">{{ $purchaseOrders->first()->payee ?? ' ' }}</span>
                    </div>
                </section>
            </header>

            <section class="w-full table-auto mt-2">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border">Quantity</th>
                            <th class="border">Unit</th>
                            <th class="border">Description</th>
                            <th class="border">Date Required</th>
                            <th class="border">Amount</th>
                            <th class="border text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalAmount = 0; @endphp

                        @foreach ($purchaseOrders as $record)
                        <tr class="border">
                            <td class="text-center border">{{ $record['quantity'] }}</td>
                            <td class="text-center border">{{ $record['unit_no'] }}</td>
                            <td class="text-center border">{{ $record['description'] }}</td>
                            <td class="text-center border">{{ $record->formatted_date_required ?? 'N/A' }}</td>
                            <td class="text-center border">{{ number_format($record['amount'], 2) }}</td>
                            <td class="text-right border">{{ number_format($record['total'], 2) }}</td>
                        </tr>
                        @php $totalAmount += $record['total']; @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right border font-bold">Grand Total:</td>
                            <td class="text-right border font-bold">{{ number_format($totalAmount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </section>

            <hr class="w-full my-2 h-1 bg-gray-400">

            <section class="grid grid-cols-3 gap-x-2">
                <span class=" w-full text-sm text-gray-700">Requested By:</span>
                <span class=" w-full text-sm text-gray-700">Verified By:</span>
                <span class=" w-full text-sm text-gray-700">Approved By:</span>
                <span class="border-b-2 border-gray-400 w-full text-sm text-gray-700 text-center mt-10 uppercase font-bold">...</span>
                <span class="border-b-2 border-gray-400 text-center text-sm text-gray-700 mt-10 uppercase font-bold">...</span>
                <span class="border-b-2 border-gray-400 text-center text-sm text-gray-700 mt-10 uppercase font-bold">...</span>
                <span class=" w-full text-sm text-gray-700 text-center">Head of Office</span>
                <span class=" w-full text-sm text-gray-700 text-center">Comptroller</span>
                <span class=" w-full text-sm text-gray-700 text-center">VP for Administrative and Student Affairs</span>
            </section>

        </main>
</x-filament-panels::page>