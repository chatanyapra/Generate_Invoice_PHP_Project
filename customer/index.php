<?php
    require('../include/header.php');
?>
<style>
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

    /* Improved form input styling */
    .form-input {
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        border-color: #3b82f6;
    }

    /* Responsive table styling */
    @media (max-width: 640px) {

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_filter {
            text-align: left !important;
            float: none !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 0.5rem;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin-top: 0.5rem;
            float: none !important;
            text-align: center !important;
        }
    }

    /* Mobile-friendly buttons */
    .action-btn {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        margin: 0.125rem;
        white-space: nowrap;
    }
</style>

<div class="flex flex-col lg:flex-row min-h-screen m-auto w-[83%] ">

    <!-- Main Content -->
    <main class="flex-1 bg-slate-100 m-auto">
        <div class="bg-white h-16 w-full border-b border-slate-200"></div>

        <div class="p-4 sm:p-6 lg:p-8">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Customers</h1>
                    <p class="text-slate-500 mt-1 text-sm sm:text-base">Manage your customer database for invoicing.</p>
                </div>
                <button id="add-customer-btn"
                    class="mt-4 sm:mt-0 flex items-center justify-center bg-blue-600 text-white font-medium sm:font-semibold py-1.5 sm:py-2 px-3 sm:px-4 rounded-lg shadow-sm hover:bg-blue-700 transition-colors duration-200 text-sm sm:text-base">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-1 sm:mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Add New Customer
                </button>
            </div>

            <!-- Search Customers -->
            <div class="mt-6 sm:mt-8 bg-white rounded-lg shadow-sm border border-slate-200 p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-slate-800">Search Customers</h3>
                <div class="relative mt-3 sm:mt-4">
                    <input type="text" id="search-customer" placeholder="Search by name, GSTIN, or state..."
                        class="form-input w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base pr-16">
                    <button id="clear-search"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs sm:text-sm font-medium text-slate-500 hover:text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear
                    </button>
                </div>
            </div>

            <!-- Customer List -->
            <div class="mt-6 sm:mt-8 bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-slate-800">Customer List</h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1"><span id="customer-count">3</span> customers found</p>
                </div>

                <div class="px-2 sm:px-4 pb-4">
                    <table id="customer-table" class="w-full text-sm">
                        <thead class="text-xs text-slate-600 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 font-medium">Name</th>
                                <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 font-medium">GSTIN</th>
                                <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 font-medium">State</th>
                                <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 font-medium">Address</th>
                                <th scope="col" class="px-3 py-2 sm:px-6 sm:py-3 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700">
                            <tr class="border-b border-slate-200 hover:bg-slate-50">
                                <td class="px-3 py-3 sm:px-6 sm:py-4 font-medium">RAJEEV KUMAR SANDEEP KUMAR JAIN SARRAF</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4">09ABQCJ6912Z1ZX</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4">Uttar Pradesh (09)</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4">SAHARANPUR</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4 text-right">
                                    <button onclick="editCustomer(1)" class="action-btn font-medium text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteCustomer(1)" class="action-btn font-medium text-red-600 hover:text-red-800">Delete</button>
                                </td>
                            </tr>
                            <tr class="border-b border-slate-200 hover:bg-slate-50">
                                <td class="px-3 py-3 sm:px-6 sm:py-4 font-medium">MOHAN LAL JEWELLERS</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4">07AABCM1234N1ZP</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4">Delhi (07)</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4">Chandni Chowk, Delhi</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4 text-right">
                                    <button onclick="editCustomer(2)" class="action-btn font-medium text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteCustomer(2)" class="action-btn font-medium text-red-600 hover:text-red-800">Delete</button>
                                </td>
                            </tr>
                            <tr class="border-b border-slate-200 hover:bg-slate-50">
                                <td class="px-3 py-3 sm:px-6 sm:py-4 font-medium">DIAMOND GEMS</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4">27AABCD1234N1ZR</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4">Maharashtra (27)</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4">Mumbai</td>
                                <td class="px-3 py-3 sm:px-6 sm:py-4 text-right">
                                    <button onclick="editCustomer(3)" class="action-btn font-medium text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteCustomer(3)" class="action-btn font-medium text-red-600 hover:text-red-800">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Customer Modal -->
<div id="customer-modal"
    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden" role="dialog"
    aria-modal="true" aria-labelledby="modal-title">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-200 flex justify-between items-start sticky top-0 bg-white z-10">
            <div>
                <h2 id="modal-title" class="text-lg font-semibold text-slate-800">Add New Customer</h2>
                <p class="text-sm text-slate-500 mt-1">Add a new customer to your database</p>
            </div>
            <button id="close-modal-btn" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <div>
                <label for="customer-name" class="block text-sm font-medium text-slate-700 mb-1">Customer Name <span class="text-red-500">*</span></label>
                <input type="text" id="customer-name" required
                    class="form-input block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Address <span class="text-red-500">*</span></label>
                <textarea id="address" rows="3" required
                    class="form-input block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
            </div>
            <div>
                <label for="gstin" class="block text-sm font-medium text-slate-700 mb-1">GSTIN (Optional)</label>
                <input type="text" id="gstin" placeholder="22AAAAA0000A1Z5"
                    class="form-input block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="state" class="block text-sm font-medium text-slate-700 mb-1">State <span class="text-red-500">*</span></label>
                    <select id="state" required
                        class="form-input block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Select State</option>
                        <option value="UP">Uttar Pradesh</option>
                        <option value="MH">Maharashtra</option>
                        <option value="DL">Delhi</option>
                        <option value="KA">Karnataka</option>
                        <option value="TN">Tamil Nadu</option>
                        <option value="GJ">Gujarat</option>
                    </select>
                </div>
                <div>
                    <label for="state-code" class="block text-sm font-medium text-slate-700 mb-1">State Code</label>
                    <input type="text" id="state-code" placeholder="Auto-filled"
                        class="form-input block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-slate-50"
                        readonly>
                </div>
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                <input type="tel" id="phone" placeholder="+91 "
                    class="form-input block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" id="email" placeholder="customer@example.com"
                    class="form-input block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-slate-50 rounded-b-lg flex justify-end items-center space-x-3 sticky bottom-0">
            <button id="cancel-btn" type="button"
                class="bg-white py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                Cancel
            </button>
            <button type="submit" id="save-customer-btn"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                Save Customer
            </button>
        </div>
    </div>
</div>

<?php
    require('../include/footer.php');
?>