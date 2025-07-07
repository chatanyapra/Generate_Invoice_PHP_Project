<?php
$id = 3;
require('../include/header.php');
?>
<style>
    /* Custom styles to match the design */
    body {
        background-color: #f0f2f5;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.3em 0.8em;
        margin-left: 2px;
        border-radius: 4px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        color: white !important;
        background: #2563eb;
        border-color: #2563eb;
    }

    .dataTables_wrapper .dataTables_info {
        color: #6b7280;
    }

    .dataTables_wrapper .dataTables_filter {
        display: none;
        /* Hide default search box */
    }

    table.dataTable thead th {
        border-bottom: 1px solid #e5e7eb;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #e5e7eb;
    }
</style>
<div class="flex flex-col lg:flex-row min-h-screen m-auto w-[83%] ">
    <!-- Main content -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <!-- Top bar -->
        <header class="h-16 bg-white border-b border-gray-200">
            <!-- can add user profile, notifications etc. here -->
        </header>

        <!-- Page content -->
        <div class="flex-1 p-6 overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">All Invoices</h1>
                    <p class="text-gray-500">View, filter, and manage your invoices</p>
                </div>
                <button id="createInvoiceBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i> Create New Invoice
                </button>
            </div>

            <!-- Filters and Table -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <!-- Filter Controls -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label for="customSearch" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" id="customSearch" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search by customer, invoice#...">
                    </div>
                    <div>
                        <label for="transactionType" class="block text-sm font-medium text-gray-700">Transaction Type</label>
                        <select id="transactionType" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option>Retail Sales</option>
                            <option>Wholesale</option>
                            <option>Returns</option>
                        </select>
                    </div>
                    <div>
                        <label for="dateRange" class="block text-sm font-medium text-gray-700">Date Range</label>
                        <select id="dateRange" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>This Month</option>
                            <option>All Time</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button id="clearFiltersBtn" class="w-full bg-white text-gray-700 px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-50 flex items-center justify-center">
                            <i class="fa-solid fa-arrows-rotate mr-2"></i> Clear Filters
                        </button>
                    </div>
                </div>

                <!-- DataTable -->
                <div class="overflow-x-auto">
                    <table id="invoicesTable" class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Invoice No.</th>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Customer</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                                <th scope="col" class="px-6 py-3">Amount</th>
                                <th scope="col" class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated by DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
<?php
require('../include/footer.php');
?>
<script src="index.js"></script>