# Library Management System

A web-based library management system built with Laravel and MySQL. It allows members to browse books, request to borrow or reserve them, manage returns and renewals, and view their borrowing history. Administrators can manage books, requests, issued books, overdue records, fines, and member accounts.

## Features

### Member Features

* Member registration and login
* Browse available books
* Search books by title, author, category, or ISBN
* View book details
* Request to borrow available books
* Reserve unavailable books
* View current and past book requests through My Books
* Request book returns
* Renew currently issued books
* Edit profile information
* Change password
* View fines associated with borrowed or lost books
* Notification unread-count support

### Administrator Features

* Admin login and logout
* Dashboard access
* Add, edit, and delete books
* View and manage member borrow, return, and reservation requests
* Approve or reject requests
* Directly issue books to members
* View all issued books
* Mark an issued book as lost
* View overdue members through the Defaulter List
* Calculate projected overdue fines
* Manage late-return fines
* Manage lost-book fines
* Mark fines as paid
* View member profiles and request history
* View unpaid fine totals per member
* View activity logs
* Edit admin profile and password

## Fine Rules

The system uses the following fine rules:

* Late return fine: ₱5.00 per overdue day
* Lost book fine: stored separately as a lost-book fine amount
* Overdue books display a projected fine before the book is returned
* Late-return fines become recorded after the return request is approved
* Administrators can mark unpaid fines as paid

## Reservation Queue

When a book has no available copies, members may submit a reservation request.

The system supports a first-in, first-out reservation queue:

1. Members reserve an unavailable book.
2. Reservations are placed in queue order.
3. When a copy is returned, the next member in the queue can be processed for issuance.

## Technologies Used

* PHP
* Laravel
* MySQL
* Blade Templates
* Bootstrap
* JavaScript
* HTML and CSS

## Requirements

Before running the project, make sure the following are installed:

* PHP
* Composer
* MySQL Server
* Node.js and npm
* Laravel-compatible environment such as XAMPP, Laragon, or PHP's built-in development server

## Installation

1. Clone the repository:

```bash
git clone https://github.com/your-username/your-repository-name.git
```

2. Open the project folder:

```bash
cd your-repository-name
```

3. Install PHP dependencies:

```bash
composer install
```

4. Install frontend dependencies:

```bash
npm install
```

5. Create the environment file:

```bash
copy .env.example .env
```

For macOS or Linux:

```bash
cp .env.example .env
```

6. Generate the application key:

```bash
php artisan key:generate
```

7. Configure the database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=librarydb
DB_USERNAME=root
DB_PASSWORD=
```

Update the username and password if your MySQL setup uses different credentials.

8. Create the database:

```sql
CREATE DATABASE librarydb;
```

9. Run migrations:

```bash
php artisan migrate
```

10. Run the frontend build process:

```bash
npm run dev
```

11. Start the Laravel server:

```bash
php artisan serve
```

12. Open the application in your browser:

```text
http://127.0.0.1:8000
```

## Database Notes

The project uses a MySQL database named:

```text
librarydb
```

Important tables include:

* `members`
* `admins`
* `books`
* `book_requests`
* `member_notifications`
* `activity_logs`

The `book_requests` table stores issue, return, reservation, overdue, late-fine, and lost-book related records.

## Admin Fine Management

The User Fines page separates fines into two categories:

* Late Return Fines
* Lost Book Fines

An administrator can select **Mark as Paid** to update a fine's status from unpaid to paid/resolved.

## Testing Overdue Books

To test an overdue record in MySQL Workbench, update the issue date and due date of an approved issue request:

```sql
USE librarydb;

UPDATE book_requests
SET issue_date = '2026-05-20',
    due_date = '2026-06-20'
WHERE book_id = 13
  AND member_id = 2
  AND type = 'issue'
  AND status = 'approved';
```

Use the correct `book_id` and `member_id` for your own test record.

## Project Structure

```text
app/
├── Http/
│   └── Controllers/
│       ├── Admin/
│       ├── Auth/
│       └── MemberBookRequestController.php
resources/
├── views/
│   ├── admin/
│   ├── auth/
│   ├── layouts/
│   └── member/
routes/
└── web.php
database/
├── migrations/
└── seeders/
```

## Main Routes

### Member Routes

```text
/register
/login
/books
/my-books
/profile
```

### Admin Routes

```text
/admin/login
/admin/dashboard
/admin/books
/admin/requests
/admin/issued-books
/admin/defaulters
/admin/fines
/admin/users
/admin/activity-log
```

## Contributors

This project was developed as a library management system project.

## License

This project is intended for academic and educational use.
