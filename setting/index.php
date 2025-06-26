<?php
    include '../include/header.php';
?>

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-6 mt-16 md:mt-0 transition-all duration-300">
      <div class="mb-4">
        <h1 class="text-xl md:text-2xl font-semibold">Settings</h1>
        <p class="text-xs md:text-sm text-gray-500">Configure your shop profile, tax rates, and system preferences</p>
      </div>

      <!-- Tabs - Made scrollable on mobile -->
      <div class="flex overflow-x-auto pb-2 mb-4 scrollbar-hide space-x-2">
        <button class="tab-button bg-blue-100 text-blue-700 px-4 py-2 rounded-md font-medium whitespace-nowrap" data-target="shopProfileTab">Shop Profile</button>
        <button class="tab-button text-gray-600 hover:text-black px-4 py-2 rounded-md font-medium whitespace-nowrap" data-target="taxRatesTab">Tax Rates</button>
        <button class="tab-button text-gray-600 hover:text-black px-4 py-2 rounded-md font-medium whitespace-nowrap" data-target="invoiceSettingsTab">Invoice Settings</button>
      </div>

      <!-- Shop Profile Form -->
      <div id="shopProfileTab" class="tab-content bg-white p-4 md:p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Shop Profile</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-600 text-sm md:text-base">Shop Name</label>
            <input type="text" value="J.V. JEWELLERS" class="w-full border rounded px-3 py-2 mt-1 text-sm md:text-base" />
          </div>
          <div>
            <label class="block text-gray-600 text-sm md:text-base">GSTIN</label>
            <input type="text" value="09ADCPV2673H1Z7" class="w-full border rounded px-3 py-2 mt-1 text-sm md:text-base" />
          </div>

          <div class="md:col-span-2">
            <label class="block text-gray-600 text-sm md:text-base">Address</label>
            <input type="text" value="SHOP NO. -2, KRISHNA HEIGHT, JAY SINGH PURA" class="w-full border rounded px-3 py-2 mt-1 text-sm md:text-base" />
          </div>

          <div>
            <label class="block text-gray-600 text-sm md:text-base">City</label>
            <input type="text" value="MATHURA" class="w-full border rounded px-3 py-2 mt-1 text-sm md:text-base" />
          </div>
          <div>
            <label class="block text-gray-600 text-sm md:text-base">State</label>
            <select class="w-full border rounded px-3 py-2 mt-1 text-sm md:text-base">
              <option selected>Uttar Pradesh</option>
            </select>
          </div>
          <div>
            <label class="block text-gray-600 text-sm md:text-base">State Code</label>
            <select class="w-full border rounded px-3 py-2 mt-1 text-sm md:text-base">
              <option selected>09 (UP)</option>
            </select>
          </div>

          <div>
            <label class="block text-gray-600 text-sm md:text-base">VAT TIN</label>
            <input type="text" value="09627100742" class="w-full border rounded px-3 py-2 mt-1 text-sm md:text-base" />
          </div>
          <div>
            <label class="block text-gray-600 text-sm md:text-base">PAN Number</label>
            <input type="text" value="ADCPV2673H" class="w-full border rounded px-3 py-2 mt-1 text-sm md:text-base" />
          </div>

          <div class="md:col-span-2">
            <label class="block text-gray-600 text-sm md:text-base">Bank Name</label>
            <input type="text" value="ICICI BANK C/A NO. 027405001417 (JVM)" class="w-full border rounded px-3 py-2 mt-1 text-sm md:text-base" />
          </div>
          <div class="md:col-span-2">
            <label class="block text-gray-600 text-sm md:text-base">Branch & IFSC Code</label>
            <input type="text" placeholder="Enter Branch & IFSC Code" class="w-full border rounded px-3 py-2 mt-1 text-sm md:text-base" />
          </div>
        </div>

        <div class="mt-6">
          <button class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 text-sm md:text-base">Save Changes</button>
        </div>
      </div>

      <!-- Tax Rates Tab -->
      <div id="taxRatesTab" class="tab-content hidden bg-white rounded shadow p-4 md:p-6">
        <div class="mb-6">
          <h2 class="text-lg md:text-xl font-semibold mb-2">Tax Rates</h2>
          <p class="text-xs md:text-sm text-gray-600 mb-4">Manage GST rates for different product categories</p>

          <div class="overflow-x-auto">
            <table class="w-full text-xs md:text-sm text-left mb-4">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-2 py-2 md:px-4">HSN Code</th>
                  <th class="px-2 py-2 md:px-4">Description</th>
                  <th class="px-2 py-2 md:px-4">CGST Rate</th>
                  <th class="px-2 py-2 md:px-4">SGST Rate</th>
                  <th class="px-2 py-2 md:px-4">IGST Rate</th>
                  <th class="px-2 py-2 md:px-4">Default</th>
                  <th class="px-2 py-2 md:px-4">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b">
                  <td class="px-2 py-2 md:px-4">7113</td>
                  <td class="px-2 py-2 md:px-4">Jewellery</td>
                  <td class="px-2 py-2 md:px-4">1.5%</td>
                  <td class="px-2 py-2 md:px-4">1.5%</td>
                  <td class="px-2 py-2 md:px-4">3%</td>
                  <td class="px-2 py-2 md:px-4">Yes</td>
                  <td class="px-2 py-2 md:px-4 text-blue-600 cursor-pointer">Edit</td>
                </tr>
                <tr class="border-b">
                  <td class="px-2 py-2 md:px-4">7106</td>
                  <td class="px-2 py-2 md:px-4">Silver</td>
                  <td class="px-2 py-2 md:px-4">1.5%</td>
                  <td class="px-2 py-2 md:px-4">1.5%</td>
                  <td class="px-2 py-2 md:px-4">3%</td>
                  <td class="px-2 py-2 md:px-4">No</td>
                  <td class="px-2 py-2 md:px-4 text-blue-600 cursor-pointer">Edit</td>
                </tr>
                <tr>
                  <td class="px-2 py-2 md:px-4">7108</td>
                  <td class="px-2 py-2 md:px-4">Gold</td>
                  <td class="px-2 py-2 md:px-4">1.5%</td>
                  <td class="px-2 py-2 md:px-4">1.5%</td>
                  <td class="px-2 py-2 md:px-4">3%</td>
                  <td class="px-2 py-2 md:px-4">No</td>
                  <td class="px-2 py-2 md:px-4 text-blue-600 cursor-pointer">Edit</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Add Tax Rate -->
        <div class="bg-gray-50 rounded shadow p-4 md:p-6 w-full">
          <h2 class="text-lg font-semibold mb-4">Add New Tax Rate</h2>
          <form class="space-y-3 md:space-y-4">
            <input type="text" placeholder="HSN Code" class="w-full px-3 py-2 border rounded text-sm md:text-base" />
            <input type="text" placeholder="Description" class="w-full px-3 py-2 border rounded text-sm md:text-base" />
            <select class="w-full px-3 py-2 border rounded text-sm md:text-base">
              <option>1.5%</option>
            </select>
            <select class="w-full px-3 py-2 border rounded text-sm md:text-base">
              <option>1.5%</option>
            </select>
            <select class="w-full px-3 py-2 border rounded text-sm md:text-base">
              <option>3%</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm md:text-base">Add Tax Rate</button>
          </form>
        </div>
      </div>

      <!-- Invoice Settings Tab -->
      <div id="invoiceSettingsTab" class="tab-content hidden bg-white rounded shadow p-4 md:p-6">
        <h2 class="text-lg md:text-xl font-semibold mb-4">Invoice Settings</h2>

        <div class="grid md:grid-cols-2 gap-4 md:gap-6">
          <div>
            <label class="block font-medium text-xs md:text-sm mb-1">Invoice Prefix</label>
            <input type="text" value="JVJ/D/" class="w-full px-3 py-2 border rounded mb-2 text-sm md:text-base" />
            <p class="text-xs md:text-sm text-gray-500">Examples: JVJ/D/, INV-, BILL-</p>
          </div>

          <div>
            <label class="block font-medium text-xs md:text-sm mb-1">Default Transaction Type</label>
            <select class="w-full px-3 py-2 border rounded text-sm md:text-base">
              <option>Retail Sales</option>
            </select>
          </div>

          <div>
            <label class="block font-medium text-xs md:text-sm mb-1">Number of Digits</label>
            <select class="w-full px-3 py-2 border rounded text-sm md:text-base">
              <option>3 (001-999)</option>
            </select>
          </div>

          <div>
            <label class="block font-medium text-xs md:text-sm mb-1">Default Input Mode</label>
            <select class="w-full px-3 py-2 border rounded text-sm md:text-base">
              <option>Component Entry</option>
            </select>
          </div>

          <div>
            <label class="block font-medium text-xs md:text-sm mb-1">Starting Number</label>
            <input type="text" value="020" class="w-full px-3 py-2 border rounded text-sm md:text-base" />
            <p class="text-xs md:text-sm text-gray-500">Next invoice will be: JVJ/D/020</p>
          </div>

          <div>
            <label class="block font-medium text-xs md:text-sm mb-1">Invoice Copies</label>
            <div class="space-y-1 text-xs md:text-sm">
              <label class="flex items-center"><input type="checkbox" class="mr-2" checked/> Original for Recipient</label>
              <label class="flex items-center"><input type="checkbox" class="mr-2" checked/> Duplicate for Transporter</label>
              <label class="flex items-center"><input type="checkbox" class="mr-2" checked/> Triplicate for Supplier</label>
            </div>
          </div>
        </div>

        <div class="mt-6">
          <button class="bg-blue-600 text-white px-6 py-2 rounded text-sm md:text-base">Save Settings</button>
        </div>
      </div>
    </main>
    <?php
        include '../include/footer.php';
    ?>