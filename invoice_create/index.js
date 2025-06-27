
// invoice create js-----------------

document.addEventListener('DOMContentLoaded', function () {
    // Form navigation
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const continueBtn = document.getElementById('continue-to-line-items');
    const backBtn = document.getElementById('back-to-invoice-details');

    // Line items functionality
    const addItemBtn = document.getElementById('add-item');
    const useExampleBtn = document.getElementById('use-example');
    const lineItemsBody = document.getElementById('line-items-body');
    const taxableValueEl = document.getElementById('taxable-value');
    const sgstAmountEl = document.getElementById('sgst-amount');
    const cgstAmountEl = document.getElementById('cgst-amount');
    const totalInvoiceEl = document.getElementById('total-invoice');
    const totalInvoiceWordsEl = document.getElementById('total-invoice-words');

    // Required fields for validation
    const requiredFields = [
        { id: 'invoice-date', errorId: 'invoice-date-error' },
        { id: 'invoice-number', errorId: 'invoice-number-error' },
        { id: 'buyer-name', errorId: 'buyer-name-error' },
        { id: 'buyer-address', errorId: 'buyer-address-error' }
    ];

    // Line items array
    let lineItems = [];

    // Initialize with step 1
    showStep(1);

    // Continue to line items button click
    continueBtn.addEventListener('click', function (e) {
        e.preventDefault();

        if (validateForm()) {
            showStep(2);
        }
    });

    // Back to invoice details button click
    backBtn.addEventListener('click', function (e) {
        e.preventDefault();
        showStep(1);
    });

    // Use example button click
    useExampleBtn.addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('quantity').value = '10.468';
        document.getElementById('rate').value = '56000.08';
    });

    // Add item button click
    addItemBtn.addEventListener('click', function (e) {
        e.preventDefault();
        addLineItem();
    });

    // Continue to review button click
    document.getElementById('continue-to-review').addEventListener('click', function (e) {
        e.preventDefault();
        if (lineItems.length === 0) {
            alert('Please add at least one line item before continuing.');
            return;
        }
        // In a real app, this would go to step 3
        alert('Invoice data is valid and ready for review!');
    });

    // Validate form function
    function validateForm() {
        let isValid = true;

        requiredFields.forEach(field => {
            const element = document.getElementById(field.id);
            const errorElement = document.getElementById(field.errorId);

            if (!element.value.trim()) {
                errorElement.textContent = 'This field is required';
                element.classList.add('border-red-500');
                isValid = false;
            } else {
                errorElement.textContent = '';
                element.classList.remove('border-red-500');
            }
        });

        return isValid;
    }

    // Show step function
    function showStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(step => {
            step.classList.remove('active');
        });

        // Reset step indicators
        document.getElementById('step1-indicator').className = 'flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-bold';
        document.getElementById('step2-indicator').className = 'flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-bold';
        document.getElementById('step1-text').className = 'ml-2 font-medium text-gray-500';
        document.getElementById('step2-text').className = 'ml-2 font-medium text-gray-500';

        // Show selected step
        if (stepNumber === 1) {
            document.getElementById('step1').classList.add('active');
            document.getElementById('step1-indicator').className = 'flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-bold';
            document.getElementById('step1-text').className = 'ml-2 font-semibold text-blue-600';
        } else if (stepNumber === 2) {
            document.getElementById('step2').classList.add('active');
            document.getElementById('step2-indicator').className = 'flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-bold';
            document.getElementById('step2-text').className = 'ml-2 font-semibold text-blue-600';
        }
    }

    // Add line item function
    function addLineItem() {
        const hsnSac = document.getElementById('hsn-sac').value;
        const description = document.getElementById('description').value;
        const quantity = parseFloat(document.getElementById('quantity').value) || 0;
        const unit = document.getElementById('unit').value;
        const rate = parseFloat(document.getElementById('rate').value) || 0;
        const taxableValue = quantity * rate;

        if (quantity <= 0 || rate <= 0) {
            alert('Please enter valid quantity and rate values.');
            return;
        }

        const newItem = {
            id: Date.now(),
            hsnSac,
            description,
            quantity,
            unit,
            rate,
            taxableValue
        };

        lineItems.push(newItem);
        updateLineItemsTable();
        updateInvoiceSummary();

        // Reset form fields
        document.getElementById('quantity').value = '0.000';
        document.getElementById('rate').value = '0.00';
    }

    // Update line items table function
    function updateLineItemsTable() {
        if (lineItems.length === 0) {
            lineItemsBody.innerHTML = `
            <tr>
              <td colspan="6" class="text-center py-10 px-6 text-gray-500">
                No items added yet
              </td>
            </tr>
          `;
            return;
        }

        lineItemsBody.innerHTML = lineItems.map(item => `
          <tr class="bg-white border-b">
            <td class="px-6 py-4">${item.hsnSac}</td>
            <td class="px-6 py-4">${item.description}</td>
            <td class="px-6 py-4">${item.quantity.toFixed(3)} ${item.unit}</td>
            <td class="px-6 py-4">₹${item.rate.toFixed(2)}</td>
            <td class="px-6 py-4">₹${item.taxableValue.toFixed(2)}</td>
            <td class="px-6 py-4">
              <button onclick="removeLineItem(${item.id})" class="text-red-500 hover:text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </td>
          </tr>
        `).join('');
    }

    // Remove line item function (attached to window for inline event handlers)
    window.removeLineItem = function (id) {
        lineItems = lineItems.filter(item => item.id !== id);
        updateLineItemsTable();
        updateInvoiceSummary();
    };

    // Update invoice summary function
    function updateInvoiceSummary() {
        const taxableValue = lineItems.reduce((sum, item) => sum + item.taxableValue, 0);
        const cgstRate = parseFloat(document.getElementById('cgst').value) || 1.5;
        const sgstRate = parseFloat(document.getElementById('sgst').value) || 1.5;

        const cgstAmount = taxableValue * (cgstRate / 100);
        const sgstAmount = taxableValue * (sgstRate / 100);
        const totalInvoice = taxableValue + cgstAmount + sgstAmount;

        taxableValueEl.textContent = `₹${taxableValue.toFixed(2)}`;
        cgstAmountEl.textContent = `₹${cgstAmount.toFixed(2)} (${cgstRate}%)`;
        sgstAmountEl.textContent = `₹${sgstAmount.toFixed(2)} (${sgstRate}%)`;
        totalInvoiceEl.textContent = `₹${totalInvoice.toFixed(2)}`;
        totalInvoiceWordsEl.textContent = numberToWords(totalInvoice) + ' Only';
    }

    // Number to words function for invoice total
    function numberToWords(num) {
        const single = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        const double = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        const formatTenth = (digit, prev) => {
            return 0 == digit ? '' : ' ' + (1 == digit ? double[prev] : tens[digit]);
        };
        const formatOther = (digit, next, denom) => {
            return (0 != digit && 1 != next ? ' ' + single[digit] : '') + (0 != next || digit > 0 ? ' ' + denom : '');
        };

        let str = '';
        let rupees = Math.floor(num);
        let paise = Math.round((num - rupees) * 100);

        str += rupees > 0 ? formatOther(Math.floor(rupees / 10000000) % 100, 0, 'Crore') : '';
        str += rupees > 0 ? formatOther(Math.floor(rupees / 100000) % 100, 0, 'Lakh') : '';
        str += rupees > 0 ? formatOther(Math.floor(rupees / 1000) % 100, 0, 'Thousand') : '';
        str += rupees > 0 ? formatOther(Math.floor(rupees / 100) % 10, 0, 'Hundred') : '';

        if (rupees > 0) {
            const tensValue = Math.floor(rupees % 100);
            if (tensValue > 0) {
                str += tensValue < 10 ? ' ' + single[tensValue] :
                    tensValue < 20 ? ' ' + double[tensValue - 10] :
                        ' ' + tens[Math.floor(tensValue / 10)] + (tensValue % 10 > 0 ? ' ' + single[tensValue % 10] : '');
            }
            str += ' Rupees';
        }

        if (paise > 0) {
            if (rupees > 0) str += ' and';
            const tensValue = Math.floor(paise % 100);
            if (tensValue > 0) {
                str += tensValue < 10 ? ' ' + single[tensValue] :
                    tensValue < 20 ? ' ' + double[tensValue - 10] :
                        ' ' + tens[Math.floor(tensValue / 10)] + (tensValue % 10 > 0 ? ' ' + single[tensValue % 10] : '');
            }
            str += ' Paise';
        }

        return str.trim() || 'Zero Rupees';
    }

    // Update summary when tax rates change
    document.getElementById('cgst').addEventListener('change', updateInvoiceSummary);
    document.getElementById('sgst').addEventListener('change', updateInvoiceSummary);
});