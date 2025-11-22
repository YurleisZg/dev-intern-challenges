# Project README

This document explains how to set up and run the application locally. The project requires a web server environment (like **Apache** or **Nginx**).

# Challenges

This project is organized into stages:

- **Stage 0 – Web Fundamentals (Basic HTML and CSS)**
- **Stage 1 – First Contact with PHP**
- **Stage 2 – Language Fundamentals (Basic PHP)**
- **Stage 3 – Data Structures, Arrays, and Strings**
- **Stage 4 – PHP + HTML: Forms and Superglobals**
---

## 📂 Project Structure

The project is organized into a root Docker configuration and specific user folders containing the exercises.

```text
.
├── docker-compose.yml       # Orchestrates the containers (Web server & PHP) 
├── dockerfile               # Custom image configuration 
├── Elkin/                   # Workspace for Elkin
│   ├── stage0/              # HTML/CSS Landing Page & Login 
│   ├── stage1/              # Basic PHP Index [cite: 7]
│   ├── stage2/              # PHP Calculator, Syntax, & Loops 
│   ├── stage3/              # PHP Arrays & Search Logic 
│   └── stage4/              # Forms & Sign Up exercises 
└── Yurleis/                 # Workspace for Yurleis
    ├── hmtl/                # HTML Auth & Landing pages 
    └── php/                 # PHP specific exercises
        ├── arrays/          # Array manipulation exercises 
        ├── calculator/      # Calculator logic
        ├── contact/         # Contact forms 
        ├── form/            # General form handling 
        ├── status/          # Login status and session handling 
        └── index.php        # Main entry point for Yurleis 
```

## 1. Prerequisites

Make sure you have the following installed and configured before proceeding:

### For the Traditional Method (XAMPP)
* **XAMPP**: A local development environment that includes Apache, MariaDB (a MySQL fork), and PHP. You can download it from [Apache Friends](https://www.apachefriends.org/index.html).

### For the Docker Method
* **Docker Desktop**: Includes **Docker Engine** and **Docker Compose**. This will allow you to manage the application and database containers.
* **Source Code**: The main project folder (where the `docker-compose.yml` file is located in section 3.2).

---

## 2. Run with XAMPP (Traditional Method)

This method is common and easy to start, but it relies on your host operating system's configuration.

### Step 2.1: Install and Start XAMPP

1.  **Installation**: Download and install XAMPP by following the instructions for your operating system (Windows, macOS, or Linux).
2.  **Start Services**: Open the **XAMPP Control Panel**.
3.  Click the **`Start`** button next to the **`Apache`** and **`MySQL`** modules. Both should show a green running status.

### Step 2.2: Configure Source Code

1.  **Move the Project**: Navigate to the XAMPP installation folder and locate the **`htdocs`** folder.
    * *Common Windows Path*: `C:\xampp\htdocs`
    * *Common macOS Path*: `/Applications/XAMPP/htdocs`
2.  Create a new folder for your project (e.g., `my_project`) inside `htdocs`.
3.  Copy **all project files** (including the folder structure) into `C:\xampp\htdocs\my_project`.

### Step 2.3: Configure the Database (MySQL)

1.  Open your web browser and navigate to: `http://localhost/phpmyadmin/`.
2.  In phpMyAdmin, create a new database with the name expected by the project (e.g., `my_database`).
3.  **Import Schema**: If your project includes an SQL schema file (`.sql`), select the newly created database, go to the **`Import`** tab, and upload the `.sql` file.
4.  **Update Credentials**: Ensure your project's database connection files (often in a configuration file like `config.php` or similar) use the default XAMPP credentials:
    * **Host**: `localhost`
    * **User**: `root`
    * **Password**: `(empty)`

### Step 2.4: Access the Project

1.  Open your web browser.
2.  Enter the URL for your project. If the project is in the `my_project` folder and the main file is `index.php`, the URL will be: `http://localhost/my_project/index.php`.

---

## 3. Run with Docker (Recommended Method)

This method uses Docker containers to create an isolated and reproducible environment, which is ideal for development and team collaboration.

### Step 3.1: Docker File Structure

Create the following files in the **root** of your project:

1.  **`Dockerfile`**: Defines the image for our PHP container.
2.  **`docker-compose.yml`**: Defines and connects the services (the web server and the database).
3.  Create a folder for the source code, for example, **`src`**, and place all your PHP, HTML, etc. files there.

#### `Dockerfile` (Example for a PHP with Apache Environment)

```dockerfile
# Use an official PHP image with Apache
FROM php:8.2-apache
# Install the MySQLi extension (necessary to connect PHP with MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql
# Enable the Apache rewrite module, if necessary for friendly URLs
RUN a2enmod rewrite
# Copy custom php.ini file (if you have one)
# COPY php.ini /usr/local/etc/php/
# The default working directory is /var/www/html