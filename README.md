
# Generate Invoice Project

A simple and user-friendly **Invoice Generator Web Application** built with **PHP, HTML, CSS, and JavaScript** that allows users to create, view, and manage invoices easily.

## 🧾 Project Overview

This project helps small businesses and freelancers generate professional invoices for their clients. It includes features to input customer details, add invoice items, calculate totals, and display the generated invoice in a printable format.

## 📂 Features

✔ Create invoices with custom details (client info, items, prices)  
✔ Dynamic calculation of totals and taxes  
✔ View all generated invoices  
✔ Print invoice directly from browser  
✔ Simple UI built with HTML & JavaScript  
✔ Backend logic handled in PHP

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Database:** (Optional) MySQL — includes SQL file for table setup  
- **AJAX:** Used for server requests without page reloads  
- **Responsive UI:** Works across devices

## 📁 Project Structure

```

📦 Generate_Invoice_Project
┣ 📂 ajax/
┣ 📂 all_invoice/
┣ 📂 assets/
┣ 📂 customer/
┣ 📂 include/
┣ 📂 invoice_create/
┣ 📂 reports/
┣ 📂 serverresponse/
┣ 📂 setting/
┣ 📂 zallhtmlfiles/
┣ 📜 index.php
┣ 📜 invoice_generate.sql
┗ other project files...

````

## 🚀 Getting Started

Follow these steps to run the project locally:

### 1️⃣ Prerequisites

Ensure you have:

- **XAMPP / WAMP / LAMP** installed
- **PHP** enabled
- (Optional) **MySQL** if using database

### 2️⃣ Clone the Repository

```bash
git clone https://github.com/chatanyapra/Generate_Invoice_Project.git
cd Generate_Invoice_Project
````

### 3️⃣ Setup Database (Optional)

If the application uses a database:

* Open **phpMyAdmin**
* Create a new database (e.g., `invoice_db`)
* Import the SQL file:

```bash
invoice_generate (1).sql
```

### 4️⃣ Configure Backend

If any database credentials or config files are needed:

* Edit the PHP config file inside `/include/` (e.g., `config.php`)
* Update database name, user & password

### 5️⃣ Run the Project

Start your local server (XAMPP/WAMP/LAMP):

* Place the project inside **htdocs** (for XAMPP)
* Go to: `http://localhost/Generate_Invoice_Project`

## 🧩 How It Works

1. Open the main interface
2. Enter customer information
3. Add item details (name, qty, price)
4. Save or generate the invoice
5. View or print from browser

## 📦 SQL Setup

If you are using the SQL dump file included:

| File                   | Description                                         |
| ---------------------- | --------------------------------------------------- |
| `invoice_generate.sql` | Contains tables for storing invoice & customer data |


