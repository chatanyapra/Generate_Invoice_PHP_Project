       $(document).ready(function() {
            // Dummy Data
            const dummyData = [{
                    id: 1,
                    invoice_no: 'INV-00123',
                    date: '2023-11-21',
                    customer: 'John Doe',
                    type: 'Retail Sales',
                    amount: '1,250.00'
                },
                {
                    id: 2,
                    invoice_no: 'INV-00124',
                    date: '2023-11-22',
                    customer: 'Jane Smith',
                    type: 'Retail Sales',
                    amount: '2,400.50'
                },
                {
                    id: 3,
                    invoice_no: 'INV-00125',
                    date: '2023-11-23',
                    customer: 'Peter Jones',
                    type: 'Wholesale',
                    amount: '15,750.00'
                },
                {
                    id: 4,
                    invoice_no: 'INV-00126',
                    date: '2023-11-24',
                    customer: 'Mary Johnson',
                    type: 'Retail Sales',
                    amount: '850.75'
                },
                {
                    id: 5,
                    invoice_no: 'INV-00127',
                    date: '2023-11-25',
                    customer: 'David Williams',
                    type: 'Returns',
                    amount: '-300.00'
                },
                {
                    id: 6,
                    invoice_no: 'INV-00128',
                    date: '2023-11-26',
                    customer: 'Sarah Brown',
                    type: 'Retail Sales',
                    amount: '5,600.00'
                },
                {
                    id: 7,
                    invoice_no: 'INV-00129',
                    date: '2023-11-27',
                    customer: 'Chris Davis',
                    type: 'Wholesale',
                    amount: '22,100.20'
                },
                {
                    id: 8,
                    invoice_no: 'INV-00130',
                    date: '2023-11-28',
                    customer: 'Mercedes Lane',
                    type: 'Retail Sales',
                    amount: '990.00'
                }
            ];

            // Initialize DataTable
            const table = $('#invoicesTable').DataTable({
                data: dummyData,
                columns: [{
                        data: 'invoice_no'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'customer'
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: 'amount',
                        className: 'text-right'
                    },
                    {
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                        <button class="text-blue-600 hover:text-blue-800 p-1 edit-btn" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800 p-1 delete-btn" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    `;
                        }
                    }
                ],
                "dom": '<"top">rt<"bottom flex justify-between items-center mt-4"ip><"clear">',
                "language": {
                    "zeroRecords": "No invoices found matching your filters",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "Showing 0 to 0 of 0 entries",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                }
            });

            // Custom Search Functionality
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Clear Filters
            $('#clearFiltersBtn').on('click', function() {
                $('#customSearch').val('');
                $('#transactionType').prop('selectedIndex', 0);
                $('#dateRange').prop('selectedIndex', 0);
                table.search('').columns().search('').draw();
            });

            // Handle Delete Action
            $('#invoicesTable tbody').on('click', '.delete-btn', function() {
                const row = table.row($(this).parents('tr'));
                const rowData = row.data();

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete invoice ${rowData.invoice_no}. You won't be able to revert this!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.remove().draw();
                        Swal.fire(
                            'Deleted!',
                            `Invoice ${rowData.invoice_no} has been deleted.`,
                            'success'
                        )
                    }
                })
            });

            // Handle Edit Action
            $('#invoicesTable tbody').on('click', '.edit-btn', function() {
                const rowData = table.row($(this).parents('tr')).data();
                Swal.fire({
                    title: 'Edit Invoice',
                    text: `You clicked edit for invoice ${rowData.invoice_no}. A full form would go here.`,
                    icon: 'info'
                });
            });

            // Handle Create New Invoice
            $('#createInvoiceBtn').on('click', function() {
                Swal.fire({
                    title: 'Create New Invoice',
                    html: `
                <input id="swal-invoice-no" class="swal2-input" placeholder="Invoice No. (e.g., INV-00131)">
                <input id="swal-customer" class="swal2-input" placeholder="Customer Name">
                <input id="swal-amount" class="swal2-input" placeholder="Amount" type="number">
                <select id="swal-type" class="swal2-input">
                    <option value="Retail Sales">Retail Sales</option>
                    <option value="Wholesale">Wholesale</option>
                    <option value="Returns">Returns</option>
                </select>
            `,
                    confirmButtonText: 'Add Invoice',
                    focusConfirm: false,
                    preConfirm: () => {
                        const invoiceNo = document.getElementById('swal-invoice-no').value;
                        const customer = document.getElementById('swal-customer').value;
                        const amount = document.getElementById('swal-amount').value;
                        if (!invoiceNo || !customer || !amount) {
                            Swal.showValidationMessage(`Please fill out all fields`);
                        }
                        return {
                            invoiceNo,
                            customer,
                            amount,
                            type: document.getElementById('swal-type').value
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const today = new Date().toISOString().slice(0, 10);
                        const newId = Math.max(...dummyData.map(d => d.id)) + 1;

                        // Add data to DataTable
                        table.row.add({
                            id: newId,
                            invoice_no: result.value.invoiceNo,
                            date: today,
                            customer: result.value.customer,
                            type: result.value.type,
                            amount: parseFloat(result.value.amount).toFixed(2)
                        }).draw(false);

                        // Also add to original data source if needed for consistency
                        dummyData.push({
                            id: newId,
                            invoice_no: result.value.invoiceNo,
                            date: today,
                            customer: result.value.customer,
                            type: result.value.type,
                            amount: parseFloat(result.value.amount).toFixed(2)
                        });

                        Swal.fire('Success!', 'New invoice has been added.', 'success');
                    }
                });
            });

        });