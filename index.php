<?php
include('./include/dbConnection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - J.V. Jewellers</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./assets/main.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .active-link {
            background-color: #e0f2fe;
            color: #2563eb;
            font-weight: 600;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans text-sm">
    <!-- Mobile Header with Hamburger -->
    <header class="md:hidden bg-white p-4 flex items-center justify-between border-b sticky top-0 z-50">
        <div class="font-bold text-lg">
            <span class="text-blue-600">🧾</span> J.V. Jewellers
        </div>
        <button id="sidebarToggle" class="hamburger p-2 focus:outline-none">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
    </header>
    <!-- Sidebar and Overlay -->
    <div class="flex min-h-screen">
        <aside class="fixed md:relative w-64 bg-white border-r transform -translate-x-full md:translate-x-0 h-screen md:h-auto">
            <div class="p-4 font-bold text-lg border-b">
                <span class="text-blue-600">🧾</span> J.V. Jewellers
                <p class="text-xs text-gray-500">GST Invoice Generator</p>
            </div>
            <nav class="flex flex-col mt-4 space-y-1 text-gray-700">
                <a href="#" class="px-4 py-2 hover:bg-gray-100 active-link">Dashboard</a>
                <a href="./invoice_create/" class="px-4 py-2 hover:bg-gray-100">Create Invoice</a>
                <a href="./all_invoice/" class="px-4 py-2 hover:bg-gray-100">All Invoices</a>
                <a href="./customer/" class="px-4 py-2 hover:bg-gray-100">Customers</a>
                <a href="./reports/" class="px-4 py-2 hover:bg-gray-100">Reports</a>
                <a href="./setting/" class="px-4 py-2 hover:bg-gray-100">Settings</a>
            </nav>

            <!-- Footer user -->
            <div class="absolute bottom-4 w-64 px-4 py-3 border-t text-gray-600 text-sm">
                <div class="font-medium">Admin User</div>
                <div class="text-xs">admin@jvjewellers.com</div>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div class="overlay"></div>
        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-8 overflow-y-auto">
            <!-- Header -->
            <header class="flex justify-between items-center mb-6 md:mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Dashboard</h2>
                    <p class="text-slate-500 text-sm md:text-base">Overview of your sales and invoices</p>
                </div>
                <div class="flex items-center gap-2 md:gap-4">
                    <div class="relative">
                        <select class="appearance-none bg-white border border-slate-300 rounded-md py-2 pl-3 pr-8 text-sm md:text-base text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option>This Month</option>
                            <option>Last Month</option>
                            <option>This Year</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-700">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    <button class="bg-blue-500 text-white text-sm md:text-base font-semibold py-2 px-3 md:px-4 rounded-md flex items-center gap-2 hover:bg-blue-600">
                        <i class="fa-solid fa-arrows-rotate"></i> <span class="hidden md:inline">Refresh</span>
                    </button>
                </div>
            </header>

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white p-4 md:p-5 rounded-lg shadow-sm flex items-center gap-3 md:gap-5">
                    <div class="bg-blue-100 text-blue-500 w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-wallet text-lg md:text-xl"></i>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs md:text-sm">Total Sales</p>
                        <div class="flex items-baseline">
                            <span class="text-xl md:text-2xl font-bold text-slate-800">₹0</span>
                            <span class="text-xs md:text-sm text-green-500 font-semibold ml-2"><i class="fa-solid fa-arrow-up"></i> 12.5%</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 md:p-5 rounded-lg shadow-sm flex items-center gap-3 md:gap-5">
                    <div class="bg-purple-100 text-purple-500 w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center">
                        <span class="font-bold text-lg md:text-xl">%</span>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs md:text-sm">Tax Collected</p>
                        <div class="flex items-baseline">
                            <span class="text-xl md:text-2xl font-bold text-slate-800">₹0</span>
                            <span class="text-xs md:text-sm text-green-500 font-semibold ml-2"><i class="fa-solid fa-arrow-up"></i> 8.3%</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 md:p-5 rounded-lg shadow-sm flex items-center gap-3 md:gap-5">
                    <div class="bg-yellow-100 text-yellow-500 w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-file-invoice text-lg md:text-xl"></i>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs md:text-sm">Invoices Generated</p>
                        <div class="flex items-baseline">
                            <span class="text-xl md:text-2xl font-bold text-slate-800">0</span>
                            <span class="text-xs md:text-sm text-green-500 font-semibold ml-2"><i class="fa-solid fa-arrow-up"></i> 5.2%</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 md:p-5 rounded-lg shadow-sm flex items-center gap-3 md:gap-5">
                    <div class="bg-green-100 text-green-500 w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-chart-simple text-lg md:text-xl"></i>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs md:text-sm">Avg. Invoice Value</p>
                        <div class="flex items-baseline">
                            <span class="text-xl md:text-2xl font-bold text-slate-800">₹0</span>
                            <span class="text-xs md:text-sm text-red-500 font-semibold ml-2"><i class="fa-solid fa-arrow-down"></i> 2.1%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Overview -->
            <div class="bg-white p-4 md:p-6 rounded-lg shadow-sm mb-6 md:mb-8">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-3">
                    <h3 class="text-lg md:text-xl font-bold text-slate-800">Sales Overview</h3>
                    <div class="flex items-center bg-slate-100 rounded-lg p-1 text-xs md:text-sm" id="sales-tabs">
                        <button class="px-3 py-1 md:px-4 md:py-1.5 rounded-md active-tab">Daily</button>
                        <button class="px-3 py-1 md:px-4 md:py-1.5 rounded-md">Weekly</button>
                        <button class="px-3 py-1 md:px-4 md:py-1.5 rounded-md">Monthly</button>
                    </div>
                </div>
                <div class="h-64 md:h-80">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="bg-white p-4 md:p-6 rounded-lg shadow-sm">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-3">
                    <h3 class="text-lg md:text-xl font-bold text-slate-800">Recent Invoices</h3>
                    <a href="#" class="text-blue-500 text-sm md:text-base font-semibold hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs md:text-sm">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                            <tr>
                                <th class="py-2 px-3 md:py-3 md:px-4">Invoice No.</th>
                                <th class="py-2 px-3 md:py-3 md:px-4">Date</th>
                                <th class="py-2 px-3 md:py-3 md:px-4">Customer</th>
                                <th class="py-2 px-3 md:py-3 md:px-4">Type</th>
                                <th class="py-2 px-3 md:py-3 md:px-4">Amount</th>
                                <th class="py-2 px-3 md:py-3 md:px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows would go here -->
                            <tr class="border-b border-slate-200">
                                <td class="py-3 px-3 md:py-4 md:px-4 text-slate-400" colspan="6">No recent invoices to display.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activate Windows Watermark -->
            <div class="fixed bottom-5 right-5 md:right-8 text-slate-400 text-xs md:text-sm">
                <p>Activate Windows</p>
                <p class="text-2xs md:text-xs">Go to Settings to activate Windows.</p>
            </div>

        </main>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function() {
            // Toggle sidebar
            $("#sidebarToggle").click(function(e) {
                e.stopPropagation();
                $("aside").toggleClass("-translate-x-full");
                $(this).toggleClass("active");
            });

            // Close sidebar when clicking outside on mobile
            $(document).click(function() {
                if ($(window).width() < 768) {
                    $("aside").addClass("-translate-x-full");
                    $("#sidebarToggle").removeClass("active");
                }
            });

            // Prevent sidebar clicks from closing it
            $("aside").click(function(e) {
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

        $(document).ready(function() {
            // Mobile sidebar toggle
            $('.menu-btn').on('click', function() {
                $('.sidebar').toggleClass('active');
                $('.overlay').toggleClass('active');
            });

            // Close sidebar when clicking overlay
            $('.overlay').on('click', function() {
                $('.sidebar').removeClass('active');
                $('.overlay').removeClass('active');
            });

            // Tab switching logic
            $('#sales-tabs button').on('click', function() {
                $('#sales-tabs button').removeClass('active-tab');
                $(this).addClass('active-tab');
            });

            // Chart.js implementation
            const ctx = document.getElementById('salesChart').getContext('2d');

            const salesData = {
                labels: ['Sep 21', 'Sep 22', 'Sep 23', 'Sep 24', 'Sep 25', 'Sep 26', 'Sep 27', 'Sep 28'],
                datasets: [{
                        label: 'Retail Sales',
                        data: [195000, 185000, 205000, 170000, 65000, 175000, 158243, 90000],
                        backgroundColor: '#4ade80', // green-400
                        borderRadius: 4,
                        borderSkipped: false,
                    },
                    {
                        label: 'Inter-city Sales',
                        data: [170000, 280000, 215000, 155000, 310000, 145000, 203070, 340000],
                        backgroundColor: '#8b5cf6', // violet-500
                        borderRadius: 4,
                        borderSkipped: false,
                    },
                    {
                        label: 'Purchase',
                        data: [45000, 115000, 50000, 125000, 110000, 55000, 59329, 180000],
                        backgroundColor: '#facc15', // yellow-400
                        borderRadius: 4,
                        borderSkipped: false,
                    }
                ]
            };

            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: salesData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            align: 'start',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 20,
                                color: '#475569', // slate-600
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            enabled: false, // Disable default tooltip
                            external: function(context) {
                                // Tooltip Element
                                let tooltipEl = document.getElementById('chartjs-tooltip');

                                if (!tooltipEl) {
                                    tooltipEl = document.createElement('div');
                                    tooltipEl.id = 'chartjs-tooltip';
                                    tooltipEl.style.opacity = 1;
                                    tooltipEl.style.pointerEvents = 'none';
                                    tooltipEl.style.position = 'absolute';
                                    tooltipEl.style.transform = 'translate(-50%, 0)';
                                    tooltipEl.style.transition = 'all .1s ease';
                                    document.body.appendChild(tooltipEl);
                                }

                                const tooltipModel = context.tooltip;
                                if (tooltipModel.opacity === 0) {
                                    tooltipEl.style.opacity = 0;
                                    return;
                                }

                                function getBody(bodyItem) {
                                    return bodyItem.lines;
                                }

                                if (tooltipModel.body) {
                                    const titleLines = tooltipModel.title || [];
                                    const bodyLines = tooltipModel.body.map(getBody);

                                    let innerHtml = '<div class="tooltip-custom">';

                                    innerHtml += '<div class="font-semibold mb-2">Date: ' + titleLines[0] + '</div>';

                                    bodyLines.forEach(function(body, i) {
                                        const colors = tooltipModel.labelColors[i];
                                        let style = 'background:' + colors.backgroundColor;
                                        style += '; border: 1px solid ' + colors.borderColor;

                                        const value = new Intl.NumberFormat('en-IN', {
                                            style: 'currency',
                                            currency: 'INR',
                                            minimumFractionDigits: 0
                                        }).format(body[0].split(': ')[1]);
                                        const label = body[0].split(': ')[0];

                                        // This is a bit of a hack to get color dots for the legend inside the tooltip
                                        let labelColor;
                                        if (label === 'Retail Sales') labelColor = '#4ade80';
                                        else if (label === 'Inter-city Sales') labelColor = '#8b5cf6';
                                        else if (label === 'Purchase') labelColor = '#facc15';

                                        innerHtml += `<div class="tooltip-custom-item">
                                                        <span class="tooltip-color-box" style="background-color: ${labelColor}"></span> 
                                                        ${value}
                                                      </div>`;
                                    });

                                    innerHtml += '</div>';
                                    tooltipEl.innerHTML = innerHtml;
                                }

                                const position = context.chart.canvas.getBoundingClientRect();
                                tooltipEl.style.opacity = 1;
                                tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
                                tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY - tooltipEl.offsetHeight - 10 + 'px';
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    if (value === 0) return '₹0';
                                    return '₹' + (value / 100000).toFixed(1) + 'L';
                                },
                                stepSize: 100000,
                                color: '#94a3b8', // slate-400
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: '#e2e8f0', // slate-200
                                drawBorder: false,
                            }
                        },
                        x: {
                            ticks: {
                                color: '#64748b', // slate-500
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                display: false,
                            }
                        }
                    },
                    barPercentage: 0.6,
                    categoryPercentage: 0.7
                }
            });
            // To mimic the hover state on Sep 27 from the screenshot
            salesChart.tooltip.setActiveElements([{
                    datasetIndex: 0,
                    index: 6
                },
                {
                    datasetIndex: 1,
                    index: 6
                },
                {
                    datasetIndex: 2,
                    index: 6
                }
            ]);
            salesChart.update();
        });
    </script>
</body>

</html>