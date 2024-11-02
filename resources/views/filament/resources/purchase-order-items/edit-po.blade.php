<x-filament-panels::page>
    @vite('resources/css/app.css')
    <main class="min-h-screen w-full bg-white rounded-sm shadow-lg p-6">
        <span class="flex justify-end items-end">
            <x-filament::input.wrapper>
                <x-filament::input.select wire:model="status">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Out for Delivery">Out for Delivery</option>
                    <option value="Completed">Completed</option>
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </span>
        <header class="w-full">
            <article class="flex flex-col items-center w-full border my-6">
                <span class="capitalize font-bona">father saturnino urios university</span>
                <span class="uppercase font-bold text-lg tracking-tight font-bona">property & maintenance office</span>
                <span class="capitalize font-bona">Butuan City</span>
            </article>
            <article class="w-full flex justify-center border mb-2">
                <span class="uppercase font-bold text-2xl underline font-bona">purchase order</span>
            </article>
        </header>
        <form wire:submit.prevent="submit" class="">
            <section class="">
                <article class="w-auto flex justify-end border space-x-2">
                    <span class="">P.O #:</span>
                    <span class="w-[10rem] border-b-2 border-gray-400">{{ $this->po_number }}</span>
                </article>
                <article class="flex justify-between w-full h-auto">
                    <div class="flex space-x-2">
                        <span class="font-medium flex justify-start items-end w-[5rem]">Supplier: </span>
                        <select
                            id="supplier_select"
                            wire:model="supplier_id"
                            class="py-2.5 px-0 w-[30rem] text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option value="" disabled selected>Select a supplier</option>
                            @foreach($suppliers as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <span class="font-medium flex justify-end items-end">Date: </span>
                        <span class="text-sm text-gray-500 flex items-end border-b w-[10rem]">{{ now()->format('Y-m-d') }}</span>
                        <input type="hidden" id="date_input" wire:model="currentDate" value="{{ now()->format('Y-m-d') }}" />
                    </div>
                </article>
                <span class="w-full">
                    <small class="mx-[5.5rem] text-gray-700">please furnish us the following listed hereunder subject to terms and conditions set forth below:</small>
                </span>
                <article class="flex justify-between w-full h-auto">
                    <div class="flex space-x-2">
                        <span class="font-medium flex justify-start items-end w-[5rem]">Terms: </span>
                        <input type="text"
                            id="terms"
                            wire:model="terms"
                            class="block w-[30rem] py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                            placeholder="Enter text here" />
                    </div>
                    <div class="flex space-x-2">
                        <span class="font-medium flex justify-end items-end">Date Required: </span>
                        <input type="date"
                            id="date_required"
                            placeholder="Select a date"
                            class="border-0 border-b border-gray-300 p-2"
                            wire:model="date_required"
                            required />
                    </div>
                </article>
            </section>

            <section class="my-4">
                <table class="border border-collapse w-full">
                    <thead class="border">
                        <tr class="bg-gray-100">
                            <th class="border p-1">Quantity</th>
                            <th class="border p-1">Description</th>
                            <th class="border p-1">Price</th>
                            <th class="border p-1">Amount</th>
                            <th class="border p-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $index => $row)
                        <tr>
                            <td class="border ">
                                <input type="number"
                                    class="border-0 border-gray-300 rounded-sm p-1 w-full focus:outline-none focus:ring-0 focus:ring-blue-200"
                                    wire:model="rows.{{ $index }}.quantity"
                                    wire:change="updateAmount({{ $index }})"
                                    required
                                    @if($record->status !== 'Pending') disabled @endif />
                            </td>
                            <td class="border w-[30rem]">
                                <input type="text"
                                    class="border-0 border-gray-300 rounded-sm p-1 w-full focus:outline-none focus:ring-0 focus:ring-blue-200"
                                    wire:model="rows.{{ $index }}.description"
                                    required
                                    @if($record->status !== 'Pending') disabled @endif />
                            </td>
                            <td class="border w-[10rem]">
                                <input type="number"
                                    class="border-0 border-gray-300 rounded-sm p-1 w-full focus:outline-none focus:ring-0 focus:ring-blue-200"
                                    wire:model="rows.{{ $index }}.price"
                                    wire:change="updateAmount({{ $index }})"
                                    required
                                    @if($record->status !== 'Pending') disabled @endif />
                            </td>
                            <td class="border w-[10rem] text-right">
                                <input type="number"
                                    class="border-0 border-gray-300 rounded-sm p-1 w-full focus:outline-none focus:ring-0 focus:ring-blue-200"
                                    wire:model="rows.{{ $index }}.amount"
                                    readonly
                                    @if($record->status !== 'Pending') disabled @endif />
                            </td>
                            <td class="border flex justify-center space-x-0 w-[10rem]">
                                <button type="button"
                                    wire:click="addRow"
                                    class="text-white bg-[#262261] px-2 mx-1 text-lg font-semibold border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:bg-blue-900"
                                    @if($record->status !== 'Pending') disabled @endif>
                                    +
                                </button>
                                @if(count($rows) > 1)
                                <button type="button"
                                    wire:click="removeRow({{ $index }})"
                                    class="text-white bg-[#262261] px-2 mx-1 text-lg font-semibold border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:bg-blue-900"
                                    @if($record->status !== 'Pending') disabled @endif>
                                    -
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="flex justify-end mt-4">
                    <span class="font-bold">Total: </span>
                    <span class="font-bold ml-2">
                        {{ number_format($total_amount, 2) }}
                    </span>
                </div>
            </section>


            <div class="flex justify-end mt-4">
                <x-filament::button type="submit" class="text-white">
                    Submit
                </x-filament::button>
            </div>
        </form>
    </main>
</x-filament-panels::page>