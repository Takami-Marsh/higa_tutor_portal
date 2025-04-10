# HiGA Tutor Portal

A web-based platform that connects students with tutors, specifically designed for academic guidance and university admissions support.

## Features

- **User Registration & Authentication**

  - Separate registration flows for tutors and students
  - Secure password handling with salt-based hashing
  - Email-based account management

- **Profile Management**
  - Language preference settings (English/Japanese/Both)
  - University preference indication (Abroad/Domestic/Both)
  - Subject specialization listing
  - Academic grade tracking (for students)

## Technology Stack

- PHP
- MySQL
- HTML/CSS
- XAMPP (Apache, MySQL, PHP)

## Setup Instructions

1. Install XAMPP on your system
2. Clone this repository into your XAMPP htdocs folder
3. Start Apache and MySQL services in XAMPP Control Panel
4. Create a new database in phpMyAdmin
5. Execute the database setup script:

   ```sql
   mysql -u your_username -p your_database_name < database_setup.sql
   ```

   Alternatively, you can copy and paste the contents of `database_setup.sql` into phpMyAdmin's SQL console.

6. Update database credentials in `db.php` with your configuration
7. Access the portal through your web browser at `http://localhost/higa_tutor_portal`

## Project Structure

- `index.php` - Landing page and main entry point
- `login.php` - User authentication
- `signup.php` - New user registration
- `admin.php` - Administrative functions
- `db.php` - Database connection handling
- `style.css` - Global styling
- `database_setup.sql` - Database schema and setup commands
