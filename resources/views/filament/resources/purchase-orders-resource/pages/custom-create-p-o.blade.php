<x-filament-panels::page>
    @vite('resources/css/app.css')
    <main class="min-w-full mx-auto bg-white shadow-lg p-6 rounded-sm">

        <!-- Form to wrap the inputs and handle submission -->
        <form wire:submit.prevent="save">
            <input type="hidden" name="po_date" wire:model.defer="po_date" value="{{ date('Y-m-d') }}">
            <div class="flex justify-end">
                <div class="flex flex-col mb-4 w-64">
                    <label for="po_number" class="text-sm font-medium text-gray-700">PO Number</label>
                    <input
                        type="text"
                        id="po_number"
                        wire:model.defer="po_number"
                        class="mt-1 block w-full border-none placeholder:text-xs text-xs placeholder:text-gray-500 shadow- focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3"
                        readonly>
                </div>
            </div>
            <div class="flex space-x-4 mb-4">
                <div class="flex-1">
                    <label for="supplier_id" class="text-sm font-medium text-gray-700">Supplier</label>
                    <select
                        id="supplier_id"
                        class="rounded-sm shadow- mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3"
                        wire:model.defer="supplier_id">
                        <option value="">Select a supplier</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label for="department_id" class="text-sm font-medium text-gray-700">Department</label>
                    <select
                        id="department_id"
                        class="rounded-sm shadow- mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3"
                        wire:model.defer="department_id"
                        required>
                        <option value="">Select a department</option>
                        @foreach (\App\Models\Departments::all() as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <section class="overflow-x-auto" x-data="{
                        rows: @entangle('rows'),
                        get sub_total() {
                            return this.rows.reduce((sum, row) => sum + (row.total || 0), 0).toFixed(2);
                        },
                        tax: 2,  // Define your tax logic here if needed
                        get over_all_total() {
                            return (parseFloat(this.sub_total) + parseFloat(this.tax)).toFixed(2);
                        },
                        addRow() {
                            this.rows.push({
                                itemNo: '',
                                description: '',
                                quantity: 0,
                                unitPrice: 0,
                                total: 0,
                                sub_total: this.sub_total, // Adding sub_total here
                                tax: this.tax, // Adding tax here
                                over_all_total: this.over_all_total // Adding over_all_total here
                            });
                        },
                        removeRow(index) {
                            if (this.rows.length > 1) {
                                this.rows.splice(index, 1);
                            }
                        }
                    }">
                <table class="min-w-full divide-y divide-gray-200 mb-6">
                    <thead>
                        <tr>
                            <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                            <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(row, index) in rows" :key="index">
                            <tr>
                                <!-- Input for item_no -->
                                <td class="whitespace-nowrap w-20">
                                    <input
                                        type="number"
                                        step="any"
                                        required
                                        x-model="row.itemNo"
                                        wire:model.defer="rows[index].itemNo"
                                        class="mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow- focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
                                </td>
                                <!-- Input for description -->
                                <td class="whitespace-nowrap px-1 w-[45rem]">
                                    <input
                                        type="text"
                                        required
                                        x-model="row.description"
                                        wire:model.defer="rows[index].description"
                                        class="mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow- focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
                                </td>
                                <!-- Input for quantity -->
                                <td class="whitespace-nowrap px-1 w-24">
                                    <input
                                        type="number"
                                        required
                                        x-model.number="row.quantity"
                                        @input="row.total = parseFloat((row.quantity * row.unitPrice).toFixed(2)) || 0"
                                        wire:model.defer="rows[index].quantity"
                                        class="mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow- focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">


                                </td>
                                <!-- Input for unit price -->
                                <td class="whitespace-nowrap px-1 w-24">
                                    <input
                                        type="number"
                                        step="any"
                                        required
                                        x-model.number="row.unitPrice"
                                        @input="row.total = parseFloat((row.quantity * row.unitPrice).toFixed(2)) || 0"
                                        wire:model.defer="rows[index].unitPrice"
                                        class="mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow- focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
                                </td>
                                <!-- Input for total -->
                                <td class="whitespace-nowrap px-1 w-[10rem]">
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        x-model.number="row.total"
                                        readonly
                                        class="mt-1 text-right block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow- focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3 bg-gray-100 p-0">
                                </td>
                                <td class="whitespace-nowrap flex justify-center space-x-1 px-1">
                                    <button
                                        @click="rows.push({ itemNo: '', description: '', quantity: 0, unitPrice: 0, total: 0 })"
                                        type="button"
                                        class="text-white bg-[#262261] px-2 my-2 text-lg font-semibold border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:bg-blue-900">
                                        +
                                    </button>
                                    <button
                                        @click="rows.length > 1 ? rows.splice(index, 1) : null"
                                        type="button"
                                        class="text-white bg-[#EE4036] px-2 my-2 text-lg font-semibold border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 hover:bg-red-600">
                                        -
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <section class="flex justify-end overflow-x-auto">
                    <article class="flex justify-end">
                        <table class="min-w-max divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-1">Subtotal</td>
                                    <td class="px-4 py-1 text-right">
                                        <input
                                            type="number"
                                            step="any"
                                            readonly
                                            x-model.number="row.sub_total"
                                            x-bind:value="sub_total"
                                            class="w-full border-none placeholder:text-xs text-xs placeholder:text-gray-500 bg-gray-100">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-1">Tax</td>
                                    <td class="px-4 py-1 text-right">
                                        <input
                                            type="number"
                                            step="any"
                                            x-model.number="row.tax"
                                            x-bind:valu="tax"
                                            class="w-full border-none placeholder:text-xs text-xs placeholder:text-gray-500 bg-gray-100">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-1 font-semibold">Overall Total</td>
                                    <td class="px-4 py-1 text-right font-semibold">
                                        <input
                                            type="number"
                                            step="any"
                                            readonly
                                            x-model.number="row.over_all_total"
                                            x-bind:value="over_all_total"
                                            class="w-full border-none placeholder:text-xs text-xs placeholder:text-gray-500 bg-gray-100">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </article>
                </section>
            </section>

            <!-- Submit Button -->
            <div class="flex justify-end mt-4">
                <x-filament::button type="submit" class="bg-[#FAAF40] text-white">
                    Submit
                </x-filament::button>
            </div>
        </form>
    </main>

</x-filament-panels::page>