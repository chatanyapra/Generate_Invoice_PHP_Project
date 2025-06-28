<?php
include('../include/dbConnection.php');
?>
<!DOCTYPE html>
<html lang="zxx">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Settings | J.V. Jewellers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../assets/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Sweetalert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .rating i {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating i.selected {
            color: #ffc107;
        }

        .error {
            color: red;
            font-size: 10px;
        }
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
                <a href="../" class="px-4 py-2 <?= $id==1 ? 'active-link' : 'hover:bg-gray-100' ?>">Dashboard</a>
                <a href="../invoice_create/" class="px-4 py-2 <?= $id==2 ? 'active-link' : 'hover:bg-gray-100' ?>">Create Invoice</a>
                <a href="../all_invoice/" class="px-4 py-2 <?= $id==3 ? 'active-link' : 'hover:bg-gray-100' ?>">All Invoices</a>
                <a href="../customer/" class="px-4 py-2 <?= $id==4 ? 'active-link' : 'hover:bg-gray-100' ?>">Customers</a>
                <a href="../reports/" class="px-4 py-2 <?= $id==5 ? 'active-link' : 'hover:bg-gray-100' ?>">Reports</a>
                <a href="../setting/" class="px-4 py-2 <?= $id==6 ? 'active-link' : 'hover:bg-gray-100' ?>">Settings</a>
            </nav>

            <!-- Footer user -->
            <div class="absolute bottom-4 w-64 px-4 py-3 border-t text-gray-600 text-sm">
                <div class="font-medium">Admin User</div>
                <div class="text-xs">admin@jvjewellers.com</div>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div class="overlay"></div>