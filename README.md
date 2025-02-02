# php-event-management-system

# Description
This is a PHP-based web application that allows users to create, manage, and participate in events. The system includes features for user authentication, event registration, attendee management, and automated email notifications.

# Features
User registration and authentication
Event creation and management
Attendee registration and updates
Admin dashboard for managing users, attendees and events
Automated email notifications for registrations
Secure password hashing and JWT authentication

# Installation
Requirements:
  PHP 5.5 or greater
  MySQL database
  Apache or any compatible web server

# Steps to Install
Clone the repository:

  git clone https://github.com/Ashik-byte/php-event-management-system.git
  
  cd Ollyo-Event-Management-System

Import the database:

  Navigate to the DB folder.
  
  Import db.sql into your MySQL database.

# Configure the application
*Open config.php and update the following constants:

  define('ADMIN_URL', 'your-admin-url');
  
  define('DB_HOST', 'your-database-host');
  
  define('DB_PORT', 'your-database-port');
  
  define('DB_USER', 'your-database-user');
  
  define('DB_PASS', 'your-database-password');
  

*If you want all emails to be sent to a fixed email address, set USER_EMAIL in config.php:

  define('USER_EMAIL', 'your-fixed-email@example.com');

Deploy the project to a server with PHP and MySQL support.
Open the application in a browser.

# Default Credentials
Admin Login:

  Email: admin@admin.com
  Password: admin

User Login:

  Email: user1@user.com
  Password: user
  
  Email: user2@user.com
  Password: user

# License
This project is licensed under the Apache 2.0 License.

# Contributions
Contributions are welcome! Feel free to fork the repository and submit pull requests.

# Contact
For any issues or feature requests, please open an issue in the GitHub repository.

# Author
Shofeul Bashar Ashik (sbashik@yahoo.com)

Senior Software Engineer, 

Dhaka, Bangladesh

https://www.linkedin.com/in/shofeul-bashar-ashik-12647b208/
