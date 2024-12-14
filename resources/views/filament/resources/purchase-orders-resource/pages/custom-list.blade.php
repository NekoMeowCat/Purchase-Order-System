<x-filament-panels::page>
    <div id="purchase-orders-table">
        {{ $this->table }}
    </div>

    <button onclick="printTable()">Print Table</button>
    <script>
        function printTable() {
            var printContents = document.getElementById('purchase-orders-table').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</x-filament-panels::page>