# Agricultural Expert System

A comprehensive web-based expert system for plant and livestock care in Nigeria.

## Features

- User-friendly interface for searching plants and livestock
- Detailed information on Nigerian plants including planting conditions, watering schedules, and care instructions
- Comprehensive livestock care guides including feeding, breeding, and health management
- Customized to-do lists based on plant selection
- WhatsApp integration for veterinary assistance
- Admin panel for managing the database
- Responsive design for all devices

## Installation

### Requirements

- XAMPP (or any server with PHP 7.4+ and MySQL)
- Web browser

### Steps

1. Clone or download this repository to your XAMPP htdocs folder
2. Create a database named `plant_expert` in phpMyAdmin
3. Import the database schema from `database/plant_expert.sql`
4. Configure the database connection in `includes/db_connect.php` if needed
5. Access the application at http://localhost/agricultural-expert-system
6. Access the admin panel at http://localhost/agricultural-expert-system/admin
   - Default login: username: `admin`, password: `admin123`

## Project Structure
