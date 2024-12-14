<x-filament-panels::page>
    @vite('resources/css/app.css')
    <main class="min-w-full mx-auto bg-white shadow-lg p-6 rounded-sm">
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <section class="grid grid-cols-3 items-center w-full mb-4">
                <div class="col-span-1">
                    <input type="file" id="canvass_form" wire:model="canvass_form" class="px-2 py-1 border-none rounded w-full">
                </div>
                <div class="col-span-1 flex flex-col items-center">
                    <span class="capitalize font-medium text-xl">fr. saturnino urios university</span>
                    <span class="capitalize">butuan city</span>
                </div>
            </section>
            <div class="flex items-end justify-end w-full">
                <span class="flex items-center justify-end space-x-2">
                    <label for="pr_number" class="text-sm font-medium text-gray-700 w-10">PR #</label>
                    <input
                        type="text"
                        id="pr_number"
                        wire:model.defer="pr_number"
                        class="block w-[10rem] py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                        readonly>
                </span>
            </div>
            <section class="flex flex-col items-center w-full mb-4">
                <span class="uppercase font-medium text-xl">purchase requisition slip</span>
                <small class="">(amount more than Php 700.00)</small>
            </section>
            <div class="flex items-center justify-between w-full mb-1">
                <div class="flex items-center space-x-2">
                    <label for="department" class="text-sm font-medium text-gray-700 w-[10rem] uppercase">Department/office:</label>
                    <input
                        type="text"
                        id="department"
                        value="{{ auth()->user()->department->name }}"
                        class="block w-[34rem] py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                        readonly>
                </div>
                <span class="flex items-center space-x-2">
                    <label for="prs_date" class="text-sm font-medium text-gray-700 uppercase">Date</label>
                    <input
                        type="date"
                        id="prs_date"
                        value="{{ date('Y-m-d') }}"
                        class="block w-[10rem] py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                        readonly>
                </span>
            </div>

            <div class="flex items-center space-x-2">
                <label for="budget_code" class="text-sm font-medium text-gray-700 w-[10rem] uppercase">budget code:</label>
                <select
                    id="budget_code"
                    wire:model.defer="budget_code"
                    class="block w-1/2 py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                    <option value="">Select a budget code</option>
                    <option value="Instructional Development Program">Instructional Development Program</option>
                    <option value="Seminar & Conferences">Seminar & Conferences</option>
                    <option value="Student Development Program">Student Development Program</option>
                    <option value="Office Expenses">Office Expenses</option>
                    <option value="Meetings">Meetings</option>
                    <option value="Repairs and Maintenance">Repairs and Maintenance</option>
                    <option value="Postage">Postage</option>
                    <option value="Transportation">Transportation</option>
                    <option value="Capital Assets">Capital Assets</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <label for="budget_code" class="text-sm font-medium text-gray-700 w-[10rem] uppercase">purpose:</label>
                <input
                    type="text"
                    id="purpose"
                    wire:model.defer="purpose"
                    class="block w-1/2 py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
            </div>
            <div class="flex items-center space-x-2">
                <label for="budget_code" class="text-sm font-medium text-gray-700 w-[10rem] uppercase">payee:</label>
                <input
                    type="text"
                    id="payee"
                    wire:model.defer="payee"
                    class="block w-1/2 py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
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
                <table class="min-w-full divide-y divide-gray-200 mb-6 my-4 border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="border p-2 py-2 border text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="border p-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="border p-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="border p-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="border p-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Date Required</th>
                            <th class="border p-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(row, index) in rows" :key="index">
                            <tr>
                                <td class="border w-10">
                                    <input type="number"
                                        class="border-0 border-gray-300 rounded-sm p-1 w-full focus:outline-none focus:ring-0 focus:ring-blue-200"
                                        x-model.number="row.quantity"
                                        @input="row.total = parseFloat((row.quantity * row.amount).toFixed(2)) || 0"
                                        wire:model.defer="rows[index].quantity"
                                        required />
                                </td>
                                <td class="border w-20">
                                    <select
                                        class="border-0 border-gray-300 rounded-sm p-1 w-full focus:outline-none focus:ring-0 focus:ring-blue-200 text-xs appearance-none"
                                        required
                                        x-model="row.unit_no"
                                        wire:model.defer="rows[index].unit_no">
                                        <option value=""></option>
                                        <option value="pc">Piece</option>
                                        <option value="box">Box</option>
                                        <option value="kg">Kilogram</option>
                                        <option value="m">Meter</option>
                                        <option value="l">Liter</option>
                                    </select>
                                </td>
                                <td class="border w-[30rem]">
                                    <input type="text"
                                        class="border-0 border-gray-300 rounded-sm p-1 w-full focus:outline-none focus:ring-0 focus:ring-blue-200"
                                        x-model="row.description"
                                        wire:model.defer="rows[index].description"
                                        required />
                                </td>
                                <td class="border w-24">
                                    <input
                                        type="number"
                                        step="any"
                                        required
                                        x-model.number="row.amount"
                                        @input="row.total = parseFloat((row.quantity * row.amount).toFixed(2)) || 0"
                                        wire:model.defer="rows[index].amount"
                                        class="border-0 border-gray-300 rounded-sm p-1 w-full focus:outline-none focus:ring-0 focus:ring-blue-200" />
                                </td>
                                <td class="border w-[5rem] text-right">
                                    <input type="number"
                                        class="border-0 border-gray-300 rounded-sm p-1 w-full focus:outline-none focus:ring-0 focus:ring-blue-200"
                                        step="0.01"
                                        min="0"
                                        x-model.number="row.total"
                                        readonly />
                                </td>
                                <td class="border text-right">
                                    <input
                                        type="date"
                                        x-model="row.date_required"
                                        wire:model.defer="rows[index].date_required"
                                        class="border-0 border-gray-300 rounded-sm p-1 w-full focus:outline-none focus:ring-0 focus:ring-blue-200" />
                                </td>
                                <td class="border flex justify-center space-x-1">
                                    <button
                                        @click="addRow()"
                                        type="button"
                                        class="text-white w-[1.5rem] bg-[#262261] text-lg font-semibold border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:bg-blue-900">
                                        <span class="flex justify-center items-center">
                                            +
                                        </span>
                                    </button>
                                    <button
                                        @click="removeRow(index)"
                                        type="button"
                                        class="text-white w-[1.5rem] bg-[#262261] text-lg font-semibold border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:bg-blue-900">
                                        <span class="flex justify-center items-center">
                                            -
                                        </span>
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
                    Save
                </x-filament::button>
            </div>
        </form>
    </main>

</x-filament-panels::page>