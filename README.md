# Loan Management System API
A RESTful API for managing loans, built with PHP and Laravel. This API allows users to perform CRUD operations on loan data and is secured using Laravel Sanctum for authentication.

## Tech Stack

- Backend: PHP (Laravel)
- Database: MySQL
- Authentication: Laravel Sanctum
- Testing: PHPUnit

## Features

- User authentication with Laravel Sanctum
- CRUD operations for managing loans
- JSON-based responses
- Structured, paginated, and formatted API responses

## Requirements

- PHP >= 8.0
- Composer
- MySQL
- Postman (optional, for API testing)

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/neilgoswami/loan-management.git
   cd loan-management
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Set up the environment**:
    - Copy `.env.example` to `.env`
    - ```bash
      cp .env.example .env
      ```
    - Update the `.env` file with your database credentials and other configuration settings.

4. **Generate an application key**:
   ```bash
   php artisan key:generate
   ```

5. **Run migrations and seeders**:
    - Run database migrations:
    - ```bash
      php artisan migrate
      ```
    - Optionally, seed the database with test data:
    - ```bash
      # Seed the database with test users, each with a random email and a password set to "password"
      php artisan db:seed
      
      # Seed the database with test loan entries specifically, using the LoanSeeder class
      php artisan db:seed --class=LoanSeeder
      ```

6. **Serve the application**:
   ```bash
   php artisan serve
   ```
The API should now be running at `http://127.0.0.1:8000`.

## Usage

### Authentication
Obtain an access token by logging in via the `/api/login` endpoint.
Revoke an access token by logging out via the `/api/logout` endpoint.
### Authorization
Include the token in the `Authorization` header as a Bearer token when making authenticated requests:
   ```makefile
   Authorization: Bearer <your-token>
   ```
