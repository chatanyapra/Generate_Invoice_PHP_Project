document.addEventListener('DOMContentLoaded', function () {
    // Form navigation elements
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    const continueBtn = document.getElementById('continue-to-line-items');
    const backBtn = document.getElementById('back-to-invoice-details');
    const continueToReviewBtn = document.getElementById('continue-to-review');
    const submitInvoiceBtn = document.getElementById('submit-invoice');

    // Line items functionality
    const addItemBtn = document.getElementById('add-item');
    const useExampleBtn = document.getElementById('use-example');
    const lineItemsBody = document.getElementById('line-items-body');
    const taxableValueEl = document.getElementById('taxable-value');
    const sgstAmountEl = document.getElementById('sgst-amount');
    const cgstAmountEl = document.getElementById('cgst-amount');
    const totalInvoiceEl = document.getElementById('total-invoice');
    const totalInvoiceWordsEl = document.getElementById('total-invoice-words');

    // Review elements
    const reviewInvoiceNumber = document.getElementById('review-invoice-number');
    const reviewInvoiceDate = document.getElementById('review-invoice-date');
    const reviewBuyerName = document.getElementById('review-buyer-name');
    const reviewLineItems = document.getElementById('review-line-items');
    const reviewTaxSummary = document.getElementById('review-tax-summary');

    // Required fields for validation
    const requiredFields = [
        { id: 'invoice-date', errorId: 'invoice-date-error' },
        { id: 'invoice-number', errorId: 'invoice-number-error' },
        { id: 'buyer-name', errorId: 'buyer-name-error' },
        { id: 'buyer-address', errorId: 'buyer-address-error' }
    ];

    // Line items array
    let lineItems = [];
    let invoiceData = {};

    // Initialize with step 1
    showStep(1);

    // Continue to line items button click
    continueBtn.addEventListener('click', function (e) {
        e.preventDefault();

        if (validateForm()) {
            // Store invoice data
            invoiceData = {
                type: document.querySelector('input[name="type"]:checked').value,
                mode: document.querySelector('input[name="mode"]:checked').value,
                invoice_date: document.getElementById('invoice-date').value,
                invoice_number: document.getElementById('invoice-number').value,
                eway_bill: document.getElementById('eway-bill').value,
                customer_id: document.getElementById('customer-select').value,
                buyer_name: document.getElementById('buyer-name').value,
                buyer_address: document.getElementById('buyer-address').value,
                buyer_gstin: document.getElementById('buyer-gstin').value,
                buyer_state: document.getElementById('buyer-state').value,
                buyer_state_code: document.getElementById('buyer-state').value === 'UP' ? '09' : '27'
            };
            
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
    continueToReviewBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (lineItems.length === 0) {
            alert('Please add at least one line item before continuing.');
            return;
        }
        
        // Update review section with all data
        updateReviewSection();
        showStep(3);
    });

    // Submit invoice button click
    submitInvoiceBtn.addEventListener('click', function (e) {
        e.preventDefault();
        submitInvoice();
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
        document.getElementById('step3-indicator').className = 'flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-bold';
        document.getElementById('step1-text').className = 'ml-2 font-medium text-gray-500';
        document.getElementById('step2-text').className = 'ml-2 font-medium text-gray-500';
        document.getElementById('step3-text').className = 'ml-2 font-medium text-gray-500';

        // Show selected step
        if (stepNumber === 1) {
            document.getElementById('step1').classList.add('active');
            document.getElementById('step1-indicator').className = 'flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-bold';
            document.getElementById('step1-text').className = 'ml-2 font-semibold text-blue-600';
        } else if (stepNumber === 2) {
            document.getElementById('step2').classList.add('active');
            document.getElementById('step2-indicator').className = 'flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-bold';
            document.getElementById('step2-text').className = 'ml-2 font-semibold text-blue-600';
        } else if (stepNumber === 3) {
            document.getElementById('step3').classList.add('active');
            document.getElementById('step3-indicator').className = 'flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-bold';
            document.getElementById('step3-text').className = 'ml-2 font-semibold text-blue-600';
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
            hsn_sac_code: hsnSac.split(' - ')[0],
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
                <td class="px-6 py-4">${item.hsn_sac_code}</td>
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

    // Update review section with all data
    function updateReviewSection() {
        reviewInvoiceNumber.textContent = invoiceData.invoice_number;
        reviewInvoiceDate.textContent = new Date(invoiceData.invoice_date).toLocaleDateString();
        reviewBuyerName.textContent = invoiceData.buyer_name;

        // Update line items in review
        reviewLineItems.innerHTML = lineItems.map(item => `
            <tr class="border-b">
                <td class="py-2">${item.hsn_sac_code}</td>
                <td class="py-2">${item.description}</td>
                <td class="py-2 text-right">${item.quantity.toFixed(3)} ${item.unit}</td>
                <td class="py-2 text-right">₹${item.rate.toFixed(2)}</td>
                <td class="py-2 text-right">₹${item.taxableValue.toFixed(2)}</td>
            </tr>
        `).join('');

        // Update tax summary in review
        const taxableValue = lineItems.reduce((sum, item) => sum + item.taxableValue, 0);
        const cgstRate = parseFloat(document.getElementById('cgst').value) || 1.5;
        const sgstRate = parseFloat(document.getElementById('sgst').value) || 1.5;
        const cgstAmount = taxableValue * (cgstRate / 100);
        const sgstAmount = taxableValue * (sgstRate / 100);
        const totalInvoice = taxableValue + cgstAmount + sgstAmount;

        reviewTaxSummary.innerHTML = `
            <div class="flex justify-between py-1">
                <span>Taxable Value:</span>
                <span>₹${taxableValue.toFixed(2)}</span>
            </div>
            <div class="flex justify-between py-1">
                <span>CGST (${cgstRate}%):</span>
                <span>₹${cgstAmount.toFixed(2)}</span>
            </div>
            <div class="flex justify-between py-1">
                <span>SGST (${sgstRate}%):</span>
                <span>₹${sgstAmount.toFixed(2)}</span>
            </div>
            <div class="border-t border-gray-200 my-2"></div>
            <div class="flex justify-between font-semibold py-1">
                <span>Total Invoice Value:</span>
                <span>₹${totalInvoice.toFixed(2)}</span>
            </div>
            <div class="text-right text-sm text-gray-500 mt-1">
                ${numberToWords(totalInvoice)} Only
            </div>
        `;
    }

    // Submit invoice function
    function submitInvoice() {
        // Prepare data for submission
        const formData = {
            ...invoiceData,
            line_items: lineItems,
            cgst_rate: parseFloat(document.getElementById('cgst').value) || 1.5,
            sgst_rate: parseFloat(document.getElementById('sgst').value) || 1.5
        };

        // Show loading state
        submitInvoiceBtn.disabled = true;
        submitInvoiceBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Processing...';

        // Submit via AJAX
        fetch('../assets/php/submit_invoice.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Invoice created successfully!');
                // Redirect to invoice list or print page
                window.location.href = 'invoices.php';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the invoice.');
        })
        .finally(() => {
            submitInvoiceBtn.disabled = false;
            submitInvoiceBtn.textContent = 'Submit Invoice';
        });
    }

    // Number to words function for invoice total
    function numberToWords(num) {
        // ... (keep the existing numberToWords function implementation)
    }

    // Update summary when tax rates change
    document.getElementById('cgst').addEventListener('change', updateInvoiceSummary);
    document.getElementById('sgst').addEventListener('change', updateInvoiceSummary);
});