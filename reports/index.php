<?php
include_once '../include/header.php';
?>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom select arrow */
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .tab-button-group button.active {
            background-color: #2563eb;
            /* bg-blue-600 */
            color: white;
        }
    </style>

    <!-- Main Content -->
    <main class="flex-1 bg-slate-100">
        <div class="bg-blue-600 h-16 w-full"></div> <!-- Blue top bar -->

        <div class="p-4 sm:p-6 lg:p-8 -mt-16">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Sales Reports</h1>
                    <p class="text-slate-200 mt-1">Analyze your sales data and trends</p>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center space-x-2">
                    <select
                        class="bg-white/20 text-white rounded-md border-slate-400/50 shadow-sm sm:text-sm focus:ring-white focus:border-white">
                        <option>This Month</option>
                        <option>Last Month</option>
                        <option>This Year</option>
                    </select>
                    <button
                        class="flex items-center justify-center bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg shadow-sm hover:bg-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd" />
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-white rounded-lg shadow-sm p-5 flex justify-between items-start">
                    <div>
                        <p class="text-sm text-slate-500">Total Sales</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1">₹0.00</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <div class="w-7 h-7 rounded-full bg-green-200"></div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white rounded-lg shadow-sm p-5 flex justify-between items-start">
                    <div>
                        <p class="text-sm text-slate-500">Total Tax Collected</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1">₹0.00</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 16l-4-4m0 0l4-4m-4 4h18m-7 4l4 4m0 0l4-4m-4 4v-8" />
                        </svg>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="bg-white rounded-lg shadow-sm p-5 flex justify-between items-start">
                    <div>
                        <p class="text-sm text-slate-500">Invoices Generated</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1">0</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="bg-white rounded-lg shadow-sm p-5 flex justify-between items-start">
                    <div>
                        <p class="text-sm text-slate-500">Avg. Invoice Value</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1">₹0.00</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filters & Tabs -->
            <div class="mt-8">
                <div class="flex items-center space-x-2 tab-button-group" id="transaction-filter">
                    <span class="text-sm font-medium text-slate-600">Filter by Transaction Type:</span>
                    <button class="text-sm font-semibold px-3 py-1 rounded-md bg-blue-600 text-white">All</button>
                    <button
                        class="text-sm font-semibold px-3 py-1 rounded-md bg-white border border-slate-300 text-slate-600 hover:bg-slate-50">Retail</button>
                    <button
                        class="text-sm font-semibold px-3 py-1 rounded-md bg-white border border-slate-300 text-slate-600 hover:bg-slate-50">Inter-city</button>
                    <button
                        class="text-sm font-semibold px-3 py-1 rounded-md bg-white border border-slate-300 text-slate-600 hover:bg-slate-50">Purchase</button>
                </div>

                <div class="mt-4 border-b border-slate-200">
                    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                        <a href="#"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm border-blue-600 text-blue-600">Sales
                            Trends</a>
                        <a href="#"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300">Sales
                            Distribution</a>
                        <a href="#"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300">Tax
                            Breakdown</a>
                    </nav>
                </div>
            </div>

            <!-- Sales Trends Chart -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-slate-200">
                <div class="p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">Sales Trends</h3>
                        <p class="text-sm text-slate-500 mt-1">View all sales trends over time</p>
                    </div>
                    <div class="mt-3 sm:mt-0 p-1 bg-slate-100 rounded-lg flex space-x-1 tab-button-group"
                        id="time-period-toggle">
                        <button class="active text-sm font-semibold px-3 py-1 rounded-md shadow-sm">Daily</button>
                        <button
                            class="text-sm font-semibold px-3 py-1 rounded-md text-slate-600 hover:bg-white">Weekly</button>
                        <button
                            class="text-sm font-semibold px-3 py-1 rounded-md text-slate-600 hover:bg-white">Monthly</button>
                    </div>
                </div>
                <div class="h-80 flex items-center justify-center text-slate-500 text-sm border-t border-slate-200">
                    No data available for the selected period
                </div>
            </div>

            <!-- Invoice List -->
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-slate-200">
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-slate-800">Invoice List</h3>
                    <p class="text-sm text-slate-500 mt-1">Recent Invoices</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-t border-slate-200">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-medium">Invoice No.</th>
                                <th scope="col" class="px-6 py-3 font-medium">Date</th>
                                <th scope="col" class="px-6 py-3 font-medium">Customer</th>
                                <th scope="col" class="px-6 py-3 font-medium">Type</th>
                                <th scope="col" class="px-6 py-3 font-medium">Amount</th>
                                <th scope="col" class="px-6 py-3 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6"
                                    class="text-center py-10 px-6 text-slate-500 border-t border-slate-200">
                                    No invoices found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php
    include_once '../include/footer.php';
    ?>