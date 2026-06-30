# iKarRental Web Application

## Overview
iKarRental is a lightweight, server-side web application built with PHP. It allows users to browse, filter, and book rental cars, while providing administrative features for inventory and booking management. The application uses JSON files for data persistence, requiring no external database setup.

## Core Features
* **User Authentication:** Secure login and registration system utilizing PHP sessions.
* **Car Inventory & Filtering:** Users can browse available vehicles and apply filters based on minimum passenger capacity, transmission type, maximum daily price (in HUF), and date availability.
* **Smart Booking System:** Logged-in users can reserve cars for specific dates. The system automatically prevents double-booking by checking against existing reservations.
* **Role-Based Access Control:**
  * **Regular Users:** Can book cars and view their personal reservation history via their profile.
  * **Administrators:** Can add new cars to the inventory and manage (view or delete) all user bookings across the platform.

## Architecture & Storage
The application relies on local JSON files for data storage:
* `cars.json`: Stores vehicle attributes (brand, model, year, transmission, fuel type, passenger capacity, daily price, and image URL).
* `bookings.json`: Records all reservation data (user email, car ID, brand, model, start date, and end date).
* `users.json`: Manages registered user credentials and administrative roles.

## Installation & Setup
1. Clone or download the repository to your local machine.
2. Ensure you have a local server environment capable of running PHP (e.g., XAMPP, WAMP, or PHP's built-in development server).
3. Place the project files in your server's document root directory (e.g., `htdocs` or `www`).
4. Ensure the PHP process has read and write permissions for the `.json` files (`cars.json`, `bookings.json`, `users.json`) to allow data saving.
5. Open your web browser and navigate to `index.php` to access the application.
