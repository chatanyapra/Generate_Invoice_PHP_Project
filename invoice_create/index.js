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
    const reviewTransactionType = document.getElementById('review-transaction-type');

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
        reviewTransactionType.textContent = invoiceData.type === 'retail' ? 'Retail Sales' :
            invoiceData.type === 'inter-city' ? 'Inter-city Sales' : 'Purchase';

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

        // Update the invoice slip preview
        updateInvoiceSlip();
    }

    function updateInvoiceSlip() {
        // Get all the data needed for the slip
        const taxableValue = lineItems.reduce((sum, item) => sum + item.taxableValue, 0);
        const cgstRate = parseFloat(document.getElementById('cgst').value) || 1.5;
        const sgstRate = parseFloat(document.getElementById('sgst').value) || 1.5;
        const cgstAmount = taxableValue * (cgstRate / 100);
        const sgstAmount = taxableValue * (sgstRate / 100);
        const totalInvoice = taxableValue + cgstAmount + sgstAmount;
        const totalQuantity = lineItems.reduce((sum, item) => sum + item.quantity, 0);
        const unit = lineItems.length > 0 ? lineItems[0].unit : 'KGS'; // Assuming all items have same unit

        // Format date as DD-MMM-YY
        const invoiceDate = new Date(invoiceData.invoice_date);
        const formattedDate = invoiceDate.toLocaleDateString('en-US', {
            day: '2-digit',
            month: 'short',
            year: '2-digit'
        }).replace(',', '');

        // Update slip fields
        document.getElementById('slip-invoice-number').textContent = invoiceData.invoice_number;
        document.getElementById('slip-invoice-date').textContent = formattedDate;
        document.getElementById('slip-buyer-name').textContent = invoiceData.buyer_name;

        // Extract city from address (simple approach)
        const addressParts = invoiceData.buyer_address.split(',');
        const city = addressParts.length > 1 ? addressParts[addressParts.length - 2].trim() : 'N/A';
        document.getElementById('slip-buyer-city').textContent = city.toUpperCase();

        document.getElementById('slip-buyer-gstin').textContent = invoiceData.buyer_gstin || 'N/A';
        document.getElementById('slip-buyer-state').textContent = invoiceData.buyer_state === 'UP' ?
            'Uttar Pradesh, Code : 09' : 'Maharashtra, Code : 27';

        // Update line items in slip
        const slipLineItems = document.getElementById('slip-line-items');
        slipLineItems.innerHTML = lineItems.map((item, index) => `
        <tr class="divide-x divide-black">
            <td class="text-center">${index + 1}</td>
            <td class="">
                <div class="flex flex-col justify-between h-full">
                    <p class="font-bold mt-1">${item.description}</p>
                    <div>
                        <div class="text-right mt-1 space-y-px pt-4">
                            <p>CGST</p>
                            <p>SGST</p>
                            <p class="font-bold">ROUNDED OFF</p>
                        </div>
                        <p class="mt-[-15px]">Less :</p>
                    </div>
                </div>
            </td>
            <td class="text-center">${item.hsn_sac_code}</td>
            <td class="text-center font-bold">${item.quantity.toFixed(3)} ${item.unit}</td>
            <td class="text-right font-bold">${item.rate.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
            <td class="text-center font-bold">${item.unit}</td>
            <td class="text-right">
                <div class="flex flex-col justify-between h-full font-bold">
                    <p class="mt-1">${item.taxableValue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</p>
                    <div class="space-y-px">
                        <p>${(item.taxableValue * (cgstRate / 100)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</p>
                        <p>${(item.taxableValue * (sgstRate / 100)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</p>
                        <p>(-)${(totalInvoice - Math.round(totalInvoice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</p>
                    </div>
                </div>
            </td>
        </tr>
    `).join('');

        // Update totals in slip
        document.getElementById('slip-total-quantity').textContent = `${totalQuantity.toFixed(3)} ${unit}`;
        document.getElementById('slip-total-amount').textContent = `₹ ${Math.round(totalInvoice).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
        document.getElementById('slip-amount-words').textContent = `Indian Rupees ${numberToWords(Math.round(totalInvoice))} Only`;

        // Update tax summary in slip
        const slipTaxSummary = document.getElementById('slip-tax-summary');
        slipTaxSummary.innerHTML = lineItems.map(item => `
        <tr class="divide-x divide-black">
            <td class="text-center">${item.hsn_sac_code}</td>
            <td class="text-right">${item.taxableValue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
            <td class="text-center">${cgstRate}%</td>
            <td class="text-right">${(item.taxableValue * (cgstRate / 100)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
            <td class="text-center">${sgstRate}%</td>
            <td class="text-right">${(item.taxableValue * (sgstRate / 100)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
            <td class="text-right">${(item.taxableValue * ((cgstRate + sgstRate) / 100)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
        </tr>
    `).join('');

        // Update tax totals
        document.getElementById('slip-taxable-value').textContent = taxableValue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById('slip-cgst-amount').textContent = cgstAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById('slip-sgst-amount').textContent = sgstAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById('slip-total-tax').textContent = (cgstAmount + sgstAmount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById('slip-tax-words').textContent = `Indian Rupees ${numberToWords(cgstAmount + sgstAmount)} Only`;
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

    // Add PDF generation functionality
    document.getElementById('generate-pdf').addEventListener('click', async function () {
        const { jsPDF } = window.jspdf;
        const invoiceContent = document.getElementById('invoice-content');
        const invoiceTitleEl = document.getElementById('invoice-title');
        const originalTitle = invoiceTitleEl.textContent;

        const copies = [
            { id: 'originalCopy', title: '(ORIGINAL FOR RECIPIENT)' },
            { id: 'duplicateCopy', title: '(DUPLICATE FOR TRANSPORTER)' },
            { id: 'triplicateCopy', title: '(TRIPLICATE FOR SUPPLIER)' }
        ];

        const selectedCopies = copies.filter(copy => document.getElementById(copy.id).checked);

        if (selectedCopies.length === 0) {
            alert('Please select at least one invoice copy to generate.');
            return;
        }

        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating...';

        // Create PDF in portrait mode (A4 size)
        const pdf = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });

        // Calculate the width of the content to scale it properly
        const pdfWidth = pdf.internal.pageSize.getWidth() - 20; // 20mm margin (10mm each side)
        const pdfHeight = pdf.internal.pageSize.getHeight();

        for (let i = 0; i < selectedCopies.length; i++) {
            const copy = selectedCopies[i];

            // Update the title on the HTML for capturing
            invoiceTitleEl.textContent = copy.title;

            // Use html2canvas to render the invoice content with proper scaling
            const canvas = await html2canvas(invoiceContent, {
                scale: 2, // Higher quality
                useCORS: true,
                logging: false,
                scrollX: 0,
                scrollY: 0,
                windowWidth: 850, // Match your invoice container width
                width: 850, // Match your invoice container width
                height: invoiceContent.scrollHeight
            });

            const imgData = canvas.toDataURL('image/png');
            const imgProps = pdf.getImageProperties(imgData);

            // Calculate aspect ratio to maintain proportions
            const imgWidth = pdfWidth;
            const imgHeight = (imgProps.height * imgWidth) / imgProps.width;

            // Add new page if not the first page
            if (i > 0) {
                pdf.addPage();
            }

            // Center the content on the page with margins
            pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
        }

        // Restore original state
        invoiceTitleEl.textContent = originalTitle;
        this.disabled = false;
        this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>Generate PDF';

        // Save the PDF
        pdf.save(`${invoiceData.invoice_number.replace(/\//g, '-')}.pdf`);
    });

    // Back to line items button
    document.getElementById('back-to-line-items').addEventListener('click', function (e) {
        e.preventDefault();
        showStep(2);
    });
});