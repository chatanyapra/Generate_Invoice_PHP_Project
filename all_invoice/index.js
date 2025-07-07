$(document).ready(function () {
    // Initialize DataTable with legacy compatibility
    const table = $('#invoicesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '../serverresponse/invoice_fetch.php',
            type: 'GET',
            data: function (d) {
                // Map new API parameters to legacy parameters
                return {
                    sEcho: d.draw,
                    iDisplayStart: d.start,
                    iDisplayLength: d.length,
                    sSearch: d.search.value,
                    transactionType: $('#transactionType').val(),
                    // Map sorting if needed
                    iSortCol_0: d.order[0]?.column,
                    sSortDir_0: d.order[0]?.dir
                };
            },
            dataSrc: function (json) {
                console.log("Server response:", json);
                return json.aaData;
            }
        },
        columns: [
            { data: 0 }, // invoice_number
            { data: 1 }, // invoice_date
            { data: 2 }, // buyer_name
            { data: 3 }, // transaction_type
            {
                data: 4, // total_invoice_value
                className: 'text-right'
            },
            {
                data: 5, // action
                className: 'text-center',
                orderable: false,
                searchable: false
            }
        ],
        dom: '<"top">rt<"bottom flex justify-between items-center mt-4"ip><"clear">',
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>',
            zeroRecords: "No matching invoices found",
            info: "Showing _START_ to _END_ of _TOTAL_ invoices",
            infoEmpty: "Showing 0 to 0 of 0 invoices",
            infoFiltered: "(filtered from _MAX_ total invoices)"
        }
    });

    // Custom search
    $('#customSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Filter change handlers
    $('#transactionType, #dateRange').on('change', function () {
        table.ajax.reload();
    });

    // Clear filters
    $('#clearFiltersBtn').on('click', function () {
        $('#customSearch').val('');
        $('#transactionType').val('').trigger('change');
        table.search('').columns().search('').draw();
    });
});

// Delete function
function deleteFunction(id, element) {
    Swal.fire({
        title: 'Delete Invoice?',
        text: "This cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'delete_invoice.php',
                method: 'POST',
                data: { id: id },
                success: function (response) {
                    if (response.success) {
                        $(element).closest('tr').remove();
                        Swal.fire('Deleted!', 'Invoice was deleted.', 'success');
                    } else {
                        Swal.fire('Error!', response.message || 'Deletion failed', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Could not connect to server', 'error');
                }
            });
        }
    });
}