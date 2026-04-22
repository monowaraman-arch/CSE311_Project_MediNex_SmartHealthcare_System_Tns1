# MediNex Smart Healthcare System

MediNex is a PHP and MySQL based smart healthcare web application for managing doctor appointments and basic patient care workflows. It supports three user roles: admin, doctor, and patient.

## Features

- Patient registration and login
- Doctor and admin login dashboards
- Appointment booking and scheduling
- Patient medical history management
- Prescription and visit summary pages
- Basic password reset flow

## Tech Stack

- PHP
- MySQL / MariaDB
- HTML, CSS, Bootstrap
- XAMPP for local development

## Project Structure

- `admin/` admin dashboard and management pages
- `doctor/` doctor dashboard and appointment tools
- `patient/` patient dashboard, booking, and medical records
- `includes/` shared helpers and auth utilities
- `css/` shared styles and fonts
- `img/` project images and icons

## Local Setup

1. Place the project inside `C:\xampp\htdocs\`.
2. Start `Apache` and `MySQL` from XAMPP.
3. Create a database named `medinex_database`.
4. Import `medinex_database (1).sql` into phpMyAdmin.
5. Open `http://localhost/MediNex_Healthcare/` in your browser.

Database connection settings are defined in `connection.php`.

## Demo Accounts

- Admin: `admin@MediNex.com` / `123`
- Doctor: `doctor@MediNex.com` / `123`
- Patient: `patient@MediNex.com` / `123`

## Notes

This project appears to be built as an academic healthcare management system focused on appointment handling, role-based dashboards, and basic patient record features.
