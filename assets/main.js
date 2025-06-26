$(function () {
    // Tab functionality
    $(".tab-button").click(function () {
        $(".tab-button").removeClass("bg-blue-100 text-blue-700").addClass("text-gray-600 hover:text-black");
        $(this).removeClass("text-gray-600 hover:text-black").addClass("bg-blue-100 text-blue-700");
        $(".tab-content").addClass("hidden");
        $("#" + $(this).data("target")).removeClass("hidden");
    });

    // Toggle sidebar
    $("#sidebarToggle").click(function (e) {
        e.stopPropagation();
        $("aside").toggleClass("-translate-x-full");
        $(this).toggleClass("active");
    });

    // Close sidebar when clicking outside on mobile
    $(document).click(function () {
        if ($(window).width() < 768) {
            $("aside").addClass("-translate-x-full");
            $("#sidebarToggle").removeClass("active");
        }
    });

    // Prevent sidebar clicks from closing it
    $("aside").click(function (e) {
        e.stopPropagation();
    });

    // Adjust layout on resize
    function handleResize() {
        if ($(window).width() >= 768) {
            $("aside").removeClass("-translate-x-full");
            $("#sidebarToggle").removeClass("active");
        }
    }

    $(window).resize(handleResize);
    handleResize(); // Run on load
});

// report page js
document.addEventListener('DOMContentLoaded', () => {
    // Function to handle toggling active class for button groups
    const handleToggle = (groupId) => {
        const group = document.getElementById(groupId);
        if (!group) return;

        const buttons = group.querySelectorAll('button');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons in the group
                buttons.forEach(btn => {
                    btn.classList.remove('active', 'bg-blue-600', 'text-white');
                    if (groupId === 'transaction-filter') {
                        btn.classList.add('bg-white', 'border', 'border-slate-300', 'text-slate-600', 'hover:bg-slate-50');
                    }
                    if (groupId === 'time-period-toggle') {
                        btn.classList.add('text-slate-600', 'hover:bg-white');
                        btn.classList.remove('shadow-sm');
                    }
                });

                // Add active class to the clicked button
                button.classList.add('active');
                if (groupId === 'transaction-filter') {
                    button.classList.remove('bg-white', 'border', 'border-slate-300', 'text-slate-600', 'hover:bg-slate-50');
                    button.classList.add('bg-blue-600', 'text-white');
                }
                if (groupId === 'time-period-toggle') {
                    button.classList.remove('text-slate-600', 'hover:bg-white');
                    button.classList.add('shadow-sm');
                }
            });
        });
    };

    // Initialize toggle functionality for both groups
    handleToggle('transaction-filter');
    handleToggle('time-period-toggle');
});

// --------customer js-------------

// State codes mapping
const stateCodes = {
    'UP': '09',
    'MH': '27',
    'DL': '07',
    'KA': '29',
    'TN': '33',
    'GJ': '24'
};

// Current customer being edited
let currentCustomerId = null;

// Sample customer data
const customers = [
    {
        id: 1,
        name: "RAJEEV KUMAR SANDEEP KUMAR JAIN SARRAF",
        address: "SAHARANPUR",
        gstin: "09ABQCJ6912Z1ZX",
        state: "UP",
        phone: "",
        email: ""
    },
    {
        id: 2,
        name: "MOHAN LAL JEWELLERS",
        address: "Chandni Chowk, Delhi",
        gstin: "07AABCM1234N1ZP",
        state: "DL",
        phone: "",
        email: ""
    },
    {
        id: 3,
        name: "DIAMOND GEMS",
        address: "Mumbai",
        gstin: "27AABCD1234N1ZR",
        state: "MH",
        phone: "",
        email: ""
    }
];

document.addEventListener('DOMContentLoaded', () => {
    const addCustomerBtn = document.getElementById('add-customer-btn');
    const customerModal = document.getElementById('customer-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const saveCustomerBtn = document.getElementById('save-customer-btn');
    const stateSelect = document.getElementById('state');
    const stateCodeInput = document.getElementById('state-code');

    // Modal functions
    const openModal = (customer = null) => {
        if (customer) {
            // Edit mode
            document.getElementById('modal-title').textContent = 'Edit Customer';
            document.getElementById('save-customer-btn').textContent = 'Update Customer';
            currentCustomerId = customer.id;

            // Fill form with customer data
            document.getElementById('customer-name').value = customer.name;
            document.getElementById('address').value = customer.address;
            document.getElementById('gstin').value = customer.gstin;
            document.getElementById('state').value = customer.state;
            document.getElementById('state-code').value = stateCodes[customer.state] || '';
            document.getElementById('phone').value = customer.phone;
            document.getElementById('email').value = customer.email;
        } else {
            // Add mode
            document.getElementById('modal-title').textContent = 'Add New Customer';
            document.getElementById('save-customer-btn').textContent = 'Add Customer';
            currentCustomerId = null;

            // Reset form
            document.getElementById('customer-form').reset();
            document.getElementById('state-code').value = '';
        }

        customerModal.classList.remove('hidden');
    };

    const closeModal = () => {
        customerModal.classList.add('hidden');
        document.getElementById('customer-form').reset();
        currentCustomerId = null;
    };

    // Event listeners
    addCustomerBtn.addEventListener('click', () => openModal());
    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Close modal when clicking on the overlay
    customerModal.addEventListener('click', (event) => {
        if (event.target === customerModal) {
            closeModal();
        }
    });

    // Close modal on 'Escape' key press
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    // Update state code when state changes
    stateSelect.addEventListener('change', function () {
        const code = stateCodes[this.value] || '';
        stateCodeInput.value = code;
    });

    // Save customer
    saveCustomerBtn.addEventListener('click', function () {
        const name = document.getElementById('customer-name').value.trim();
        const address = document.getElementById('address').value.trim();
        const state = document.getElementById('state').value;

        // Simple validation
        if (!name || !address || !state) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all required fields',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        // In a real app, you would save to a database here
        // For demo, we'll just show a success message
        Swal.fire({
            icon: 'success',
            title: currentCustomerId ? 'Customer Updated' : 'Customer Added',
            text: currentCustomerId ? 'Customer details have been updated.' : 'New customer has been added.',
            confirmButtonColor: '#3b82f6'
        });

        closeModal();
    });
});

// Edit customer function
function editCustomer(id) {
    const customer = customers.find(c => c.id === id);
    if (customer) {
        openModal(customer);
    }
}

// Delete customer function with SweetAlert confirmation
function deleteCustomer(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // In a real app, you would delete from database here
            // For demo, we'll just show a success message
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'Customer has been deleted.',
                confirmButtonColor: '#3b82f6'
            });
        }
    });
}

// Form element (added dynamically since it wasn't in the original HTML)
const form = document.createElement('form');
form.id = 'customer-form';
form.innerHTML = document.getElementById('customer-modal').querySelector('.p-6').innerHTML;
document.getElementById('customer-modal').querySelector('.p-6').replaceWith(form);