# School System

This project is a School Management System developed with the Laravel framework, designed to streamline school operations by managing users, classes, subjects, timetables, and roles. The system provides distinct views and functionalities for **_Admins_**, **_Teachers_**, **_Parents_**, and **_Students_**, with role-based access control powered by Spatie's Laravel Permission package. Key features include:

-   **Admin Panel**: For managing users, classes, subjects, and timetables.
-   **Teacher Interface**: Class and teaching schedule management.
-   **Student and Parent Dashboards**: Viewing timetables, class details, and personal information.

This project is tailored to facilitate efficient school management, offering a centralized platform for administrators, teachers, students, and parents to interact with essential school data in real time.

## Getting Started

Follow these instructions to get a copy of the project up and running on your local machine for development and testing.

### Prerequisites

-   **PHP 8.2 or higher**
-   **Composer** for PHP dependency management
-   **MySQL or another supported database**

### Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/PhongN18/School-System.git
    cd School_System

    ```

2. **Install PHP dependencies with Composer:**

    ```bash
    composer install

    ```

3. **Set up the environment configuration:**

    ```bash
    cp .env.example .env

    or copy manually

    Open the .env file and update the following database and application settings as needed:
    APP_NAME=School-System
    APP_URL=http://localhost

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password

    ```

4. **Generate an application key:**

    ```bash
    php artisan key:generate

    ```

5. **Set up the database:**

    ```bash
    php artisan migrate
    php artisan db:seed

    ```

6. **Serve the application locally:**

    ```bash
    php artisan serve

    Open your browser and go to http://localhost:8000.
    ```
