# Loan Management API
A RESTful API for managing loans, built with PHP and Laravel. This API allows users to perform CRUD operations on loan data and is secured using Laravel Sanctum for authentication.

## Tech Stack

- **Backend**: Laravel 11.x (PHP)
- **Database**: MySQL
- **Authentication**: Laravel Sanctum for API token-based authentication
- **Testing**: PHPUnit for unit and feature tests

## Features

### Phase 1
- **User Authentication**: Basic user setup with Laravel Sanctum.
- **Loan Management**: Users can create, view, update, and delete loans.
- **Testing**: Basic unit and feature tests for key functionalities.

### Phase 2
- **Lender-Borrower Association**: Each loan must be associated with a lender and a borrower.
- **Access Control**: Only the original lender can edit or delete their own loans.
- **Public Access**: Authentication is not required for viewing loans.

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
   ```bash
   php artisan migrate --seed
   ```
   Seeders include a test user account with the following credentials:
   - **Email**: engineer@nedhelps.com
   - **Password**: password
      
7. **Serve the application**:
   ```bash
   php artisan serve
   ```
The API should now be running at `http://127.0.0.1:8000`.

## API Endpoints

| Method      | Endpoint            | Description                   | Authentication |
|-------------|---------------------|-------------------------------|----------------|
| POST        | /api/login          | User login                    | No             |
| POST        | /api/logout         | User logout                   | Yes            |
| GET         | /api/v1/loans       | Get a list of loans           | No             |
| GET         | /api/v1/loans/{id}  | Get details of a specific loan| No             |
| POST        | /api/v1/loans       | Create a new loan             | Yes            |
| PUT / PATCH | /api/v1/loans/{id}  | Update a loan                 | Yes            |
| DELETE      | /api/v1/loans/{id}  | Delete a loan                 | Yes            |

## Testing
To run tests, use the following command:
   ```bash
   php artisan test
   ```

### Key Test Cases
Happy Path Scenarios
- **View Loan List**: Verify that the loan list can be accessed without authentication.
- **Loan Creation**: Ensure a valid user can create a loan with a lender and borrower.
- **Loan Editing**: Validate that only the original lender can update their loan details.
- **Loan Deletion**: Confirm that only the original lender can delete their loan.
