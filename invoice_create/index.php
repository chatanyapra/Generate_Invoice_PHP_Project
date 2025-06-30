<?php
$id = 2;
require('../include/header.php');
?>
<style>
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

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .invoice-container {
        width: 850px;
        /* Slightly wider than A4 (794px) to match image proportions */
        margin: 2rem auto;
        height: 1179px;
        background: white;
        padding: 2.5rem;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
    }

    /* All tables have collapsed borders for a clean look */
    table {
        border-collapse: collapse;
        table-layout: fixed;
        width: 100%;
    }

    /* Default border style for all table cells */
    th,
    td {
        border: 1px solid black;
        padding: 4px 8px;
        vertical-align: top;
    }

    /* Utility class to remove the top border from a table, allowing it to merge with the container above */
    .no-top-border {
        border-top: none;
    }

    .no-top-border th,
    .no-top-border td {
        border-top: none;
    }
</style>
<div class="flex flex-col lg:flex-row min-h-screen m-auto w-[83%] ">
    <!-- Main Content -->
    <main class="flex-1 p-6">
        <h1 class="text-2xl font-semibold mb-2">Create New Invoice</h1>

        <!-- Stepper -->
        <div class="mt-8 mb-6 w-full">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm">
                    <div id="step1-indicator" class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-bold">1</div>
                    <span id="step1-text" class="ml-2 font-semibold text-blue-600">Invoice Details</span>
                </div>
                <div class="flex-auto border-t-2 border-gray-200 mx-4"></div>
                <div class="flex items-center text-sm">
                    <div id="step2-indicator" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-bold">2</div>
                    <span id="step2-text" class="ml-2 font-medium text-gray-500">Line Items</span>
                </div>
                <div class="flex-auto border-t-2 border-gray-200 mx-4"></div>
                <div class="flex items-center text-sm">
                    <div id="step3-indicator" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-bold">3</div>
                    <span id="step3-text" class="ml-2 font-medium text-gray-500">Review & Generate</span>
                </div>
            </div>
        </div>

        <!-- Form Steps -->
        <div class="form-steps">
            <!-- Step 1: Invoice Details -->
            <div id="step1" class="form-step active">
                <p class="text-sm text-gray-500 mb-4">Step 1: Invoice Details</p>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Section -->
                    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-4">Transaction Information</h2>

                        <div class="mb-4">
                            <label class="block font-medium mb-1">Transaction Type</label>
                            <div class="flex space-x-4">
                                <label><input type="radio" name="type" value="retail" checked /> Retail Sales</label>
                                <label><input type="radio" name="type" value="inter-city" /> Inter-city Sales</label>
                                <label><input type="radio" name="type" value="purchase" /> Purchase</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-1">Input Mode</label>
                            <div class="flex space-x-4">
                                <label><input type="radio" name="mode" value="component" checked /> Component Entry</label>
                                <label><input type="radio" name="mode" value="direct" /> Direct Amount Entry</label>
                                <label><input type="radio" name="mode" value="reverse" /> Reverse Calculation</label>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block">Invoice Date <span class="text-red-500">*</span></label>
                                <input type="date" id="invoice-date" class="w-full border rounded px-3 py-2 mt-1" required />
                                <div id="invoice-date-error" class="error-message"></div>
                            </div>
                            <div>
                                <label class="block">Invoice Number <span class="text-red-500">*</span></label>
                                <input type="text" id="invoice-number" value="JVJ/021" class="w-full border rounded px-3 py-2 mt-1" required />
                                <div id="invoice-number-error" class="error-message"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block">E-way Bill Number (Optional)</label>
                            <input type="text" id="eway-bill" class="w-full border rounded px-3 py-2 mt-1" />
                        </div>

                        <h2 class="text-lg font-semibold mb-4">Buyer Information</h2>
                        <div class="mb-4">
                            <label class="block">Select Customer</label>
                            <select id="customer-select" class="w-full border rounded px-3 py-2 mt-1">
                                <option value="">Select a customer</option>
                                <option value="1">Customer 1</option>
                                <option value="2">Customer 2</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block">Buyer Name <span class="text-red-500">*</span></label>
                            <input type="text" id="buyer-name" class="w-full border rounded px-3 py-2 mt-1" required />
                            <div id="buyer-name-error" class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="block">Address <span class="text-red-500">*</span></label>
                            <textarea id="buyer-address" class="w-full border rounded px-3 py-2 mt-1" required></textarea>
                            <div id="buyer-address-error" class="error-message"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block">GSTIN</label>
                                <input type="text" id="buyer-gstin" class="w-full border rounded px-3 py-2 mt-1" />
                            </div>
                            <div>
                                <label class="block">State & Code</label>
                                <select id="buyer-state" class="w-full border rounded px-3 py-2 mt-1">
                                    <option value="">Select State</option>
                                    <option value="UP">Uttar Pradesh (09)</option>
                                    <option value="MH">Maharashtra (27)</option>
                                </select>
                            </div>
                        </div>

                        <div class="bg-gray-100 text-gray-600 p-2 rounded text-sm mb-4">Tax Type: CGST + SGST</div>

                        <button id="continue-to-line-items" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Continue to Line Items →</button>
                    </div>

                    <!-- Right Sidebar -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-4">Information</h2>
                        <p class="text-sm mb-2">
                            <strong>J.V. JEWELLERS</strong><br>
                            SHOP NO. -2, KRISHNA HEIGHT, JAY SINGH PURA<br>
                            MATHURA<br>
                            GSTIN: 09ADCPV2673H1Z7<br>
                            State: Uttar Pradesh (09)
                        </p>

                        <h3 class="font-semibold mt-4 mb-2">Transaction Types</h3>
                        <ul class="text-sm list-disc pl-5 space-y-1">
                            <li><span class="text-blue-500">Retail Sales:</span> CGST + SGST</li>
                            <li><span class="text-purple-500">Inter-city Sales:</span> IGST</li>
                            <li><span class="text-yellow-500">Purchase:</span> Inward procurement</li>
                        </ul>

                        <h3 class="font-semibold mt-4 mb-2">Input Modes</h3>
                        <ul class="text-sm list-disc pl-5 space-y-1">
                            <li><span class="text-blue-500">Component Entry:</span> Auto-calculated</li>
                            <li><span class="text-gray-700">Direct Amount:</span> Enter total manually</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Step 2: Line Items -->
            <div id="step2" class="form-step">
                <p class="text-sm text-gray-500 mb-4">Step 2: Add Line Items</p>

                <!-- Add Line Items -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Add Line Items</h3>
                        <p class="text-sm text-gray-500 mt-2">Component Entry Mode</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 mt-4">
                            <div class="xl:col-span-1">
                                <label for="hsn-sac" class="text-sm font-medium text-gray-600">HSN/SAC Code</label>
                                <select id="hsn-sac" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option>7113 - Jewellery</option>
                                    <option>7114 - Precious Stones</option>
                                </select>
                            </div>
                            <div class="md:col-span-2 xl:col-span-1">
                                <label for="description" class="text-sm font-medium text-gray-600">Description</label>
                                <input type="text" id="description" value="SILVER ORNAMENTS" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="quantity" class="text-sm font-medium text-gray-600">Quantity</label>
                                <input type="text" id="quantity" value="0.000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="unit" class="text-sm font-medium text-gray-600">Unit</label>
                                <select id="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option>KGS</option>
                                    <option>GMS</option>
                                    <option>PCS</option>
                                </select>
                            </div>
                            <div>
                                <label for="rate" class="text-sm font-medium text-gray-600">Rate per KGS</label>
                                <input type="text" id="rate" value="0.00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div class="flex items-end xl:col-span-5">
                                <button id="add-item" class="w-full xl:w-auto mt-4 xl:mt-0 ml-auto bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow-sm hover:bg-green-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Add Item
                                </button>
                            </div>
                        </div>
                        <div class="mt-6 p-4 bg-green-50 rounded-lg flex items-center justify-between">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-green-800">Example: 10.468 KGS of Silver Ornaments at ₹56,000.08 per KG = ₹5,86,208.83 taxable value</p>
                            </div>
                            <a href="#" id="use-example" class="ml-4 text-sm font-semibold text-green-700 hover:text-green-800 whitespace-nowrap">Use Example</a>
                        </div>
                    </div>
                </div>

                <!-- Line Items Table -->
                <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">HSN/SAC</th>
                                    <th scope="col" class="px-6 py-3">Description</th>
                                    <th scope="col" class="px-6 py-3">Quantity</th>
                                    <th scope="col" class="px-6 py-3">Rate</th>
                                    <th scope="col" class="px-6 py-3">Taxable Value</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="line-items-body">
                                <tr>
                                    <td colspan="6" class="text-center py-10 px-6 text-gray-500">
                                        No items added yet
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tax Configuration -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold mb-4">Tax Configuration</h3>
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="cgst" class="text-sm font-medium text-gray-600">CGST Rate (%)</label>
                                <select id="cgst" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option>1.5%</option>
                                    <option>2.5%</option>
                                    <option>6%</option>
                                </select>
                            </div>
                            <div>
                                <label for="sgst" class="text-sm font-medium text-gray-600">SGST Rate (%)</label>
                                <select id="sgst" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option>1.5%</option>
                                    <option>2.5%</option>
                                    <option>6%</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2 sm:flex sm:justify-end">
                                <div class="w-full sm:w-1/2">
                                    <label for="igst" class="text-sm font-medium text-gray-600">IGST Rate (%)</label>
                                    <select id="igst" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option>3%</option>
                                        <option>5%</option>
                                        <option>12%</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Summary -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold mb-4">Invoice Summary</h3>
                        <div class="mt-4 text-gray-700">
                            <div class="flex justify-between text-sm">
                                <span>Taxable Value:</span>
                                <span id="taxable-value" class="font-medium">₹0.00</span>
                            </div>
                            <div class="flex justify-between text-sm mt-2">
                                <span>SGST (1.5%):</span>
                                <span id="sgst-amount" class="font-medium">₹0.00</span>
                            </div>
                            <div class="flex justify-between text-sm mt-2">
                                <span>CGST (1.5%):</span>
                                <span id="cgst-amount" class="font-medium">₹0.00</span>
                            </div>
                            <div class="flex justify-between text-sm mt-2">
                                <span>Rounding Adjustment:</span>
                                <span id="rounding" class="font-medium">₹0.00</span>
                            </div>
                            <div class="border-t border-gray-200 my-4"></div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold">Total Invoice Value:</span>
                                <span id="total-invoice" class="text-2xl font-bold">₹0.00</span>
                            </div>
                            <p id="total-invoice-words" class="text-right text-sm text-gray-500 mt-1">Zero Rupees Only</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="mt-8 flex flex-col-reverse sm:flex-row justify-between items-center">
                    <button id="back-to-invoice-details" class="mt-4 sm:mt-0 font-semibold text-gray-600 py-2 px-4 rounded-lg hover:bg-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Invoice Details
                    </button>
                    <button id="continue-to-review" class="w-full sm:w-auto bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-sm hover:bg-blue-700 flex items-center justify-center">
                        Continue to Review
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Step 3: Review & Generate -->
            <div id="step3" class="form-step">
                <p class="text-sm text-gray-500 mb-4">Step 3: Review & Generate Invoice</p>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-6">Invoice Review</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-medium mb-2">Invoice Details</h3>
                            <div class="space-y-2">
                                <p><strong>Invoice Number:</strong> <span id="review-invoice-number"></span></p>
                                <p><strong>Invoice Date:</strong> <span id="review-invoice-date"></span></p>
                                <p><strong>Transaction Type:</strong> <span id="review-transaction-type"></span></p>
                                <p><strong>Buyer Name:</strong> <span id="review-buyer-name"></span></p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium mb-2">Tax Summary</h3>
                            <div id="review-tax-summary" class="space-y-1"></div>
                        </div>
                    </div>

                    <h3 class="text-lg font-medium mb-2">Line Items</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-2 py-3">HSN/SAC</th>
                                    <th class="px-2 py-3">Description</th>
                                    <th class="px-2 py-3 text-right">Quantity</th>
                                    <th class="px-2 py-3 text-right">Rate</th>
                                    <th class="px-2 py-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="review-line-items"></tbody>
                        </table>
                    </div>

                    <!-- Invoice Slip Preview -->
                    <!-- Invoice Slip Preview -->
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-medium mb-4">Invoice Slip Preview</h3>

                        <div id="invoice-slip-container" class="border rounded-lg p-4 bg-gray-50">
                            <div id="invoice-content" class="bg-white p-4 mx-auto" style="width: 210mm; transform: scale(0.8); transform-origin: top left;">
                                <!-- Header Section -->
                                <div class="flex justify-between items-center">
                                    <div class="w-[40%]"></div>
                                    <div class="flex justify-between items-center w-[60%]">
                                        <div class="text-center font-bold text-[16px] tracking-wide">Tax Invoice</div>
                                        <i id="invoice-title" class="text-right text-xs">(ORIGINAL FOR RECIPIENT)</i>
                                    </div>
                                </div>

                                <!-- Main Info Block -->
                                <div class="grid grid-cols-2 border border-black h-[220px]">
                                    <!-- Left Column: Seller and Buyer Info -->
                                    <div class="py-1 border-r border-black text-xs px-0.5">
                                        <div class="font-bold">J.V. JEWELLERS</div>
                                        <div>SHOP NO. -2, KRISHNA HIEGHT, JAY SINGH PURA</div>
                                        <div>MATHURA</div>
                                        <div class="">GSTIN/UIN: 09ADCPV2673H1Z7</div>
                                        <div>State Name : Uttar Pradesh, Code : 09</div>
                                        <hr class="my-0.5 border-t border-black">
                                        <div class="font-bold">Buyer (Bill to)</div>
                                        <div class="font-bold mt-1" id="slip-buyer-name"></div>
                                        <div class="font-bold" id="slip-buyer-city"></div>
                                        <div class="mt-1">GSTIN/UIN: <span id="slip-buyer-gstin"></span></div>
                                        <div>State Name: <span id="slip-buyer-state"></span></div>
                                    </div>

                                    <!-- Right Column: Invoice Metadata Table -->
                                    <div>
                                        <table class="text-xs">
                                            <tbody>
                                                <tr class="divide-x divide-black">
                                                    <td class="w-1/2 border-0 border-b border-black">
                                                        <p>Invoice No.</p>
                                                        <strong class="text-sm font-bold" id="slip-invoice-number">JVJ/D/019</strong>
                                                    </td>
                                                    <td class="w-1/2 border-0 border-b border-r-0 border-black">
                                                        <p>Dated</p>
                                                        <strong class="text-sm font-bold" id="slip-invoice-date">27-Sep-24</strong>
                                                    </td>
                                                </tr>
                                                <tr class="divide-x divide-black text-[11px]">
                                                    <td class="border-b border-black">
                                                        <p>Delivery Note</p>
                                                        <div class="h-4"></div>
                                                    </td>
                                                    <td class="border-b border-r-0 border-black">
                                                        <p>Mode/Terms of Payment</p>
                                                        <div class="h-4"></div>
                                                    </td>
                                                </tr>
                                                <tr class="divide-x divide-black text-[11px]">
                                                    <td class="border-b border-black">
                                                        <p>Buyer's Order No.</p>
                                                        <div class="h-4"></div>
                                                    </td>
                                                    <td class="border-b border-r-0 border-black">
                                                        <p>Dated</p>
                                                        <div class="h-4"></div>
                                                    </td>
                                                </tr>
                                                <tr class="divide-x divide-black text-[11px]">
                                                    <td colspan="2" class="border-b-0 border-r-0">
                                                        <p>Terms of Delivery</p>
                                                        <div class="h-4"></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Items Table -->
                                <table class="border border-black border-t-0">
                                    <thead>
                                        <tr class="divide-x divide-black text-center font-bold">
                                            <td class="w-[5%]">Sl No.</td>
                                            <td class="w-[35%]">Description of Goods</td>
                                            <td class="w-[10%]">HSN/SAC</td>
                                            <td class="w-[11%]">Quantity</td>
                                            <td class="w-[11%]">Rate</td>
                                            <td class="w-[8%]">per</td>
                                            <td class="w-[15%]">Amount</td>
                                        </tr>
                                    </thead>
                                    <tbody id="slip-line-items" class="h-[420px]">
                                        <!-- Line items will be inserted here by JavaScript -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="divide-x divide-black font-bold">
                                            <td colspan="3" class="text-center">Total</td>
                                            <td class="text-center" id="slip-total-quantity">10.468 KGS</td>
                                            <td colspan="2"></td>
                                            <td class="text-right">
                                                <div class="flex justify-between items-center">
                                                    <span id="slip-total-amount">₹ 6,03,795.00</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" class="font-bold p-2">
                                                <div class="flex justify-between">
                                                    <span>
                                                        Amount Chargeable (in words)<br>
                                                        <span class="font-[700] text-[13px]" id="slip-amount-words">Indian Rupees Six Lakh Three Thousand Seven Hundred Ninety Five Only</span>
                                                    </span>
                                                    <span class="font-normal text-[10px] ml-4">E. & O.E</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <!-- Tax Summary Table -->
                                <table class="no-top-border border border-black border-t-0">
                                    <thead>
                                        <tr class="divide-x divide-black text-center font-bold">
                                            <td rowspan="2" class="align-middle w-[280px]">HSN/SAC</td>
                                            <td rowspan="2" class="align-middle">Taxable Value</td>
                                            <td colspan="2">CGST</td>
                                            <td colspan="2">SGST/UTGST</td>
                                            <td rowspan="2" class="align-middle">Total Tax Amount</td>
                                        </tr>
                                        <tr class="divide-x divide-black text-center font-bold">
                                            <td>Rate</td>
                                            <td>Amount</td>
                                            <td>Rate</td>
                                            <td>Amount</td>
                                        </tr>
                                    </thead>
                                    <tbody id="slip-tax-summary">
                                        <!-- Tax summary will be inserted here by JavaScript -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="divide-x divide-black font-[800]">
                                            <td class="text-center">Total</td>
                                            <td class="text-right" id="slip-taxable-value">5,86,208.83</td>
                                            <td class="text-right" colspan="2" id="slip-cgst-amount">8,793.13</td>
                                            <td class="text-right" colspan="2" id="slip-sgst-amount">8,793.13</td>
                                            <td class="text-right" id="slip-total-tax">17,586.26</td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="border border-y-0 border-black p-2">
                                    <p class="text-[12px]">Tax Amount (in words) : <span class="font-[700] text-[13px]" id="slip-tax-words">Indian Rupees Seventeen Thousand Five Hundred Eighty Six and Twenty Six paise Only</span></p>
                                </div>

                                <div class="grid grid-cols-2 border border-t-0 border-black text-[12px] h-[155px]">
                                    <div class="p-2">
                                        <p>Company's VAT TIN               : <b>09627100742</b></p>
                                        <p>Buyer's VAT TIN                     : <b>09871300591</b></p>
                                        <p>Company's PAN                     : <b>ADCPV2673H</b></p>
                                        <br>
                                        <p class="font-bold underline">Declaration</p>
                                        <p>We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</p>
                                    </div>
                                    <div class="flex flex-col justify-between">
                                        <div>
                                            <p>Company's Bank Details</p>
                                            <p>Bank Name             : ICICI BANK C/A NO. 027405001417 (JVM)</p>
                                            <p>A/c No.                    : </p>
                                            <p>Branch & IFS Code : </p>
                                        </div>
                                        <div class="text-end border-t border-black py-0.5 px-2 border-l border-black">
                                            <p class="font-bold pb-7">for J.V. JEWELLERS</p>
                                            <p class="">Authorised Signatory</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4 text-xs font-semibold">This is a Computer Generated Invoice</div>
                            </div>
                        </div>
                    </div>

                    <!-- Generation Options -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium mb-2">Generation Options</h3>

                        <div class="mb-4">
                            <label class="block font-medium mb-2">Invoice Copies</label>
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="originalCopy" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <span class="ml-2">Original for Recipient</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="duplicateCopy" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <span class="ml-2">Duplicate for Transporter</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="triplicateCopy" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <span class="ml-2">Triplicate for Supplier</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Navigation -->
                    <div class="mt-8 flex flex-col-reverse sm:flex-row justify-between items-center">
                        <button id="back-to-line-items" class="mt-4 sm:mt-0 font-semibold text-gray-600 py-2 px-4 rounded-lg hover:bg-gray-100 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Line Items
                        </button>
                        <div>
                            <button id="generate-pdf" class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg shadow-sm hover:bg-blue-700 flex items-center justify-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                                Generate PDF
                            </button>
                            <button id="submit-invoice" class="bg-green-600 text-white font-semibold py-2 px-6 rounded-lg shadow-sm hover:bg-green-700 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Submit Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
require('../include/footer.php');
?>
<script src="./index.js"></script>
<!-- Add these in the head section of your create-invoice.php -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>