<x-filament-panels::page>
    @vite('resources/css/app.css')
    <main class="min-w-full mx-auto bg-white shadow-lg p-6 rounded-sm">
        <section class="flex flex-col items-center w-full mb-4">
            <span class="capitalize font-medium text-xl">fr. saturnino urios university</span>
            <span class="capitalize">butuan city</span>
        </section>
        <!-- Form to wrap the inputs and handle submission -->
        <form wire:submit.prevent="save">
            <div class="flex items-end justify-end w-full">
                <span class="flex items-center justify-end space-x-2">
                    <label for="pr_number" class="text-sm font-medium text-gray-700 w-10">PR #</label>
                    <input
                        type="text"
                        id="pr_number"
                        wire:model.defer="pr_number"
                        class="mt-1 block w-full border-none placeholder:text-xs text-xs placeholder:text-gray-500 shadow focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3"
                        readonly>
                </span>
            </div>
            <section class="flex flex-col items-center w-full mb-4">
                <span class="uppercase font-medium text-xl">purchase requisition slip</span>
                <small class="">(amount more than Php 700.00)</small>
            </section>
            <div class="flex items-center justify-between w-full mb-1">
                <div class="flex items-center space-x-2">
                    <label for="department_id" class="text-sm font-medium text-gray-700 w-[10rem] uppercase">Department/office:</label>
                    <select
                        id="department_id"
                        class="mt-1 w-[34rem] border-none placeholder:text-xs text-xs placeholder:text-gray-500 shadow focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3"
                        wire:model.defer="department_id"
                        required>
                        <option value="">Select a department</option>
                        @foreach (\App\Models\Departments::all() as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <span class="flex items-center space-x-2">
                    <label for="prs_date" class="text-sm font-medium text-gray-700 uppercase">Date</label>
                    <input
                        type="date"
                        id="prs_date"
                        x-model.number="row.prs_date"
                        wire:model.defer="rows[index].prs_date"
                        class="mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow-sm focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
                </span>
            </div>

            <div class="flex items-center space-x-2">
                <label for="budget_code" class="text-sm font-medium text-gray-700 w-[10rem] uppercase">budget code:</label>
                <input
                    type="text"
                    id="budget_code"
                    x-model.number="row.budget_code"
                    wire:model.defer="budget_code"
                    class="mt-1 block w-1/2 border-none placeholder:text-xs text-xs placeholder:text-gray-500 shadow focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
            </div>
            <div class="flex items-center space-x-2">
                <label for="budget_code" class="text-sm font-medium text-gray-700 w-[10rem] uppercase">purpose:</label>
                <input
                    type="text"
                    id="purpose"
                    wire:model.defer="purpose"
                    class="mt-1 block w-1/2 border-none placeholder:text-xs text-xs placeholder:text-gray-500 shadow focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
            </div>
            <div class="flex items-center space-x-2">
                <label for="budget_code" class="text-sm font-medium text-gray-700 w-[10rem] uppercase">payee:</label>
                <input
                    type="text"
                    id="payee"
                    wire:model.defer="payee"
                    class="mt-1 block w-1/2 border-none placeholder:text-xs text-xs placeholder:text-gray-500 shadow focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
            </div>


            <section class="overflow-x-auto" x-data="{
                        rows: @entangle('rows'),
                        get over_all_total() {
                            return this.rows.reduce((sum, row) => sum + (row.total || 0), 0).toFixed(2);
                        },
                        addRow() {
                            this.rows.push({
                                unit_no: '',
                                description: '',
                                budget_code: '',
                                prs_date: '',
                                quantity: 0,
                                amount: 0,
                                total: 0,
                                date_required: ''
                            });
                        },
                        removeRow(index) {
                            if (this.rows.length > 1) {
                                this.rows.splice(index, 1);
                            }
                        }
                    }">
                <table class="min-w-full divide-y divide-gray-200 mb-6 my-4">
                    <thead>
                        <tr>
                            <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Date Required</th>
                            <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(row, index) in rows" :key="index">
                            <tr>
                                <!-- Input for Quantity -->
                                <td class="whitespace-nowrap">
                                    <input
                                        type="number"
                                        required
                                        x-model.number="row.quantity"
                                        @input="row.total = parseFloat((row.quantity * row.amount).toFixed(2)) || 0"
                                        wire:model.defer="rows[index].quantity"
                                        class="mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow-sm focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
                                </td>

                                <!-- Input for Unit -->
                                <td class="whitespace-nowrap w-24">
                                    <input
                                        type="number"
                                        required
                                        x-model.number="row.unit_no"
                                        x-model="row.unit_no"
                                        wire:model.defer="rows[index].unit_no"
                                        class="mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow-sm focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
                                </td>

                                <!-- Input for Description -->
                                <td class="whitespace-nowrap w-[45rem]">
                                    <input
                                        type="text"
                                        required
                                        x-model="row.description"
                                        wire:model.defer="rows[index].description"
                                        class="mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow-sm focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
                                </td>

                                <!-- Input for Amount -->
                                <td class="whitespace-nowrap w-24">
                                    <input
                                        type="number"
                                        step="any"
                                        required
                                        x-model.number="row.amount"
                                        @input="row.total = parseFloat((row.quantity * row.amount).toFixed(2)) || 0"
                                        wire:model.defer="rows[index].amount"
                                        class="mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow-sm focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
                                </td>

                                <!-- Input for Total -->
                                <td class="whitespace-nowrap w-[10rem] text-right">
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        x-model.number="row.total"
                                        readonly
                                        class="mt-1 text-right block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow-sm focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3 bg-gray-100">
                                </td>

                                <!-- Input for Date Required -->
                                <td class="whitespace-nowrap text-right">
                                    <input
                                        type="date"
                                        x-model="row.date_required"
                                        wire:model.defer="rows[index].date_required"
                                        class="mt-1 block w-full border border-gray-300 placeholder:text-xs text-xs placeholder:text-gray-500 shadow-sm focus:ring-indigo-100 focus:border-indigo-500 sm:text-sm py-3">
                                </td>

                                <!-- Actions -->
                                <td class="whitespace-nowrap flex justify-center space-x-1">
                                    <button
                                        @click="addRow()"
                                        type="button"
                                        class="text-white bg-[#262261] px-2 my-2 text-lg font-semibold border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:bg-blue-900">
                                        +
                                    </button>
                                    <button
                                        @click="removeRow(index)"
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
                                    <td class="px-4 py-1 font-semibold">Overall Total</td>
                                    <td class="px-4 py-1 text-right font-semibold">
                                        <input
                                            type="number"
                                            step="any"
                                            readonly
                                            x-bind:value="over_all_total"
                                            class="w-full border-none placeholder:text-xs text-xs placeholder:text-gray-500 bg-gray-100 text-right">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </article>
                </section>
            </section>


            <!-- Submit Button -->
            <div class="flex justify-end mt-4">
                <x-filament::button type="submit" class="text-white">
                    Submit
                </x-filament::button>
            </div>
        </form>
    </main>

</x-filament-panels::page>