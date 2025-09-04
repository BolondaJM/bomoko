# DRC Embassy Management System (BOMOKO - Kenya)

This is a web-based management system for the DRC Embassy in Nairobi, Kenya. The system is designed to manage DRC citizens residing in Kenya, facilitate visa applications for Kenyans wishing to travel to the DRC, handle incident reports, and provide an admin dashboard for embassy staff.

## Features

- **User Registration**: DRC citizens can register with detailed information (first name, middle name, last name, gender, date of birth, place of birth, passport number, visa type, phone, email, residential address, and county).
    - **Validation:**
        - Date of birth must be before today (cannot be today or in the future).
        - Passport number must start with 'OP'.
- **Login**: Registered users can log in using their email and password (passwords are hashed client-side).
- **Account Creation**: When setting a password, it must be at least 8 characters and contain both letters and numbers (enforced client-side).
- **Visa Application**: Kenyans can apply for a visa to the DRC, providing personal details, travel information, and uploading required documents (PDF only).
    - **Validation:**
        - Travel date must be after today (cannot be today or in the past).
- **Incident Report**: DRC citizens in Kenya can report incidents (lost passport, legal, medical, etc.).
- **Admin Dashboard**: Embassy staff can view, add, edit, and delete registered citizens and view incident reports.
- **Contact Us**: Embassy contact information, Google Map, and a message form.
- **Responsive Design**: Modern, clean, and mobile-friendly interface.

## Folder Structure

```
assets/                # Images and static files (e.g., DRC logo)
index.html             # Main landing page
login.html             # Login page
register.html          # Registration form for DRC citizens
create_account.html    # Set password after registration
registration_complete.html # Registration success page
visa.html              # Visa application form (step 1)
visa_upload.html       # Visa document upload and terms (step 2)
visa_complete.html     # Visa application confirmation
incident.html          # Incident report form
admin.html             # Admin dashboard
contact.html           # Contact Us page
styles.css             # Main CSS file
README.md              # This file
```

## Setup & Usage

1. **Requirements**: This is a static HTML/CSS/JS project. You can run it on any web server (e.g., XAMPP, Apache, Nginx) or open the HTML files directly in your browser.
2. **Assets**: Place images (e.g., `drc logo.png`) in the `assets/` folder.
3. **Navigation**: All pages are linked via the navigation bar at the top.
4. **Forms**: Forms are static and do not store data unless connected to a backend. Passwords are hashed in the browser for demonstration.
5. **Client-side Validation**: Registration, account creation, and visa application forms include client-side validation for date of birth, passport number, password, and travel date.
6. **Google Map**: The Contact Us page embeds a Google Map for the embassy location.
7. **Document Upload**: Visa applicants can upload PDF documents (no backend storage by default).

## Customization & Backend Integration
- To make the system fully functional (store users, handle logins, process forms, etc.), connect the forms to a backend (PHP, Node.js, Python, etc.) and a database (MySQL, PostgreSQL, etc.).
- Update email addresses, phone numbers, and address as needed for your embassy.

## Contact
- **Embassy Address**: Riverside Drive, Nairobi, Kenya
- **Email**: info@drcembassy.co.ke
- **Phone**: +254 712 345 678

## MySQL Database Setup

1. Open phpMyAdmin (http://localhost/phpmyadmin) or use the MySQL command line.
2. Run the following SQL to create the database and tables:

```sql
CREATE DATABASE IF NOT EXISTS bomoko_db;
USE bomoko_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    middle_name VARCHAR(100),
    last_name VARCHAR(100),
    gender VARCHAR(20),
    dob DATE,
    place_of_birth VARCHAR(100),
    passport_number VARCHAR(50),
    visa_type VARCHAR(50),
    phone VARCHAR(30),
    email VARCHAR(100),
    residential_address VARCHAR(255),
    county VARCHAR(50),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin TINYINT(1) DEFAULT 0
);

CREATE TABLE IF NOT EXISTS visa_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100),
    email VARCHAR(100),
    gender VARCHAR(20),
    dob DATE,
    country VARCHAR(100),
    passport_number VARCHAR(50),
    visa_type VARCHAR(50),
    duration VARCHAR(50),
    travel_date DATE,
    reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS incident_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(30),
    incident_type VARCHAR(50),
    description TEXT,
    incident_date DATE,
    incident_address VARCHAR(255),
    reported_police ENUM('yes','no'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS visa_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100),
    passport_pdf VARCHAR(255),
    supporting_pdf VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- To support admin validation, add a status column to visa_applications:
ALTER TABLE visa_applications ADD COLUMN status VARCHAR(20) DEFAULT 'pending';

-- To support admin assignment, add an is_admin column to users:
ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0;
```

3. Update your PHP files to use the correct database credentials.

## PHP Backend Integration
- Place your PHP files in the same directory as your HTML files (htdocs/bomoko/).
- Use the provided database connection script (see below) in all PHP files that interact with the database.

## Sample PHP Database Connection (db_connect.php)

```php
<?php
$host = 'localhost';
$db   = 'bomoko_db';
$user = 'root'; // default XAMPP user
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
     throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
```

---

*BOMOKO - Kenya | DRC Embassy Management System | 2025* 