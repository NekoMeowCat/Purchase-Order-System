 <div class="space-y-4">
     <h2 class="text-xl font-semibold">Purchase Order Details</h2>

     <div class="grid grid-cols-2 gap-4">
         <div>
             <strong>PO Number:</strong> {{ $record->po_number }}
             <!-- Add other main record details -->
         </div>
     </div>

     <div class="mt-4">
         <h3 class="text-lg font-medium mb-2">Related Items</h3>
         <table class="w-full border-collapse">
             <thead>
                 <tr class="bg-gray-100">
                     <th class="border p-2">Item</th>
                     <th class="border p-2">Quantity</th>
                     <th class="border p-2">Price</th>
                     <!-- Add more columns as needed -->
                 </tr>
             </thead>
             <tbody>
                 @foreach($record->related_items as $item)
                 <tr>
                     <td class="border p-2">{{ $item->description }}</td>
                     <td class="border p-2">{{ $item->quantity }}</td>
                     <td class="border p-2">{{ $item->price }}</td>
                     <!-- Add more columns as needed -->
                 </tr>
                 @endforeach
             </tbody>
         </table>
     </div>
 </div>