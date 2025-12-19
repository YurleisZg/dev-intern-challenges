# Project README

This document explains how to set up and run the application locally. The project requires a web server environment (like **Apache** or **Nginx**).

## Challenges Overview

This project is organized into stages:

- **Stage 0 – Web Fundamentals (Basic HTML and CSS)**
- **Stage 1 – First Contact with PHP**
- **Stage 2 – Language Fundamentals (Basic PHP)**
- **Stage 3 – Data Structures, Arrays, and Strings**
- **Stage 4 – PHP + HTML: Forms and Superglobals**

In addition, there are specific challenge folders under `Elkin/Challenge/`:

- **FirstChallenge – Salary Calculator with Auth & Composer**
  - **Path**: `Elkin/Challenge/FirstChallenge/`
  - **Entry point**: `index.php`
  - **What it does**: Simple authentication (login/register) plus a salary calculator with overtime and bonus logic, using Composer for autoloading (`composer.json`).
  - **How to run (XAMPP example)**:
    - Copy the whole repo into `C:\xampp\htdocs\dev-intern-challenges`.
    - Ensure the database configuration in `Elkin/Challenge/FirstChallenge/config/app.json` matches your MySQL credentials.
    - Install Composer dependencies from that folder (if needed): `composer install`.
    - Open in browser: `http://localhost/dev-intern-challenges/Elkin/Challenge/FirstChallenge/index.php`.

- **ThirdChallenge – Pattern Memory Game**
  - **Path**: `Elkin/Challenge/ThirdChallenge/`
  - **Main page**: `challenge3.php`
  - **What it does**: A “pattern memory” game where the user creates a 5-row pattern and must reproduce it within a time limit, with strikes and levels (`pattern_setup.php`, `pattern_play.php`, `pattern_success.php`).
  - **How to run (XAMPP example)**:
    - With the project under `C:\xampp\htdocs\dev-intern-challenges`.
    - Open in browser: `http://localhost/dev-intern-challenges/Elkin/Challenge/ThirdChallenge/challenge3.php`.

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
│   ├── stage4/              # Forms & Sign Up exercises 
│   ├── stage5/              # Sessions, login status & auth-related exercises
│   ├── stage6/              # Advanced / extra exercises for Elkin
│   └── Challenge/           # Challenge folders for Elkin
│       ├── FirstChallenge/  # Salary Calculator with Auth & Composer
│       └── ThirdChallenge/  # Pattern Memory Game
└── Yurleis/                 # Workspace for Yurleis
    ├── index.php            # Main entry point in root
    ├── styles/              # CSS styles directory
    ├── stage0/              # HTML Auth & Landing pages 
    └── php/                 # PHP specific exercises
        ├── stage1/          # Basic PHP Index
        ├── stage2/          # Calculator logic, Syntax, & Loops 
        ├── stage3/          # Array manipulation exercises 
        ├── stage4/          # General form handling 
        ├── stage5/          # Login status and session handling 
        └── challenges/      # Exercise folders
```

## 1. Prerequisites

Make sure you have the following installed and configured before proceeding:

### For the Traditional Method (XAMPP)
* **XAMPP**: A local development environment that includes Apache, MariaDB (a MySQL fork), and PHP. You can download it from [Apache Friends](https://www.apachefriends.org/index.html).

### For the Docker Method
* **Docker Desktop**: Includes **Docker Engine** and **Docker Compose**. This will allow you to manage the application and database containers.
* **Source Code**: The main project folder (where the `docker-compose.yml` file is located).

> **📖 For detailed Docker instructions, see [DOCKER_GUIDE.md](DOCKER_GUIDE.md)**

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


# Docker Setup Guide

This guide provides step-by-step instructions for running the project using Docker. This is the **recommended method** for development as it ensures consistency across all team members' environments.

## Prerequisites

- **Docker Desktop**: Download from [Docker's official website](https://www.docker.com/products/docker-desktop)
- Ensure Docker is installed and running on your system
- Basic familiarity with PowerShell/Terminal

---

## Quick Start Commands

Follow these commands **in order** after pulling the project:

### Step 1: Navigate to the project folder

```powershell
cd "C:\Users\[YOUR_USERNAME]\PhpstormProjects\dev-intern-challenges"
```

### Step 2: Verify Docker is installed

```powershell
docker --version
docker-compose --version
```

Both should display version numbers (e.g., `Docker version 24.0.0`).

### Step 3: Clean up previous containers (if any)

```powershell
docker-compose down -v
docker system prune -f
```

This ensures you're starting fresh with a clean state.

### Step 4: Build and start all containers

```powershell
docker-compose up --build -d
```

**This command will:**
- Build the PHP/Apache Docker image
- Download and start MySQL database
- Download and start phpMyAdmin
- Start the PHP web server
- Automatically initialize both databases:
  - `phpaccountant` (FirstChallenge)
  - `thirdchallenge` (ThirdChallenge)

The `-d` flag runs containers in the background.

### Step 5: Wait for MySQL to initialize (2-3 minutes)

```powershell
Start-Sleep -Seconds 10
docker-compose logs mysql
```

**You should see this message:**
```
ready for connections. Version: '8.4.7'
```

If you don't see it, wait another minute and run the command again.

### Step 6: Verify all containers are running

```powershell
docker-compose ps
```

**Expected output:**
```
NAME                COMMAND             STATUS
mysql              "docker-entrypoint..." Up 2 minutes
phpmyadmin         "/docker-entrypoint..." Up 2 minutes
first_steps_php    "apache2-foreground"  Up 2 minutes
```

All three should show `Up`.

---

## Access Your Applications

Once Docker is running successfully, open your browser and navigate to:

### FirstChallenge - Salary Calculator with Authentication

- **URL**: http://localhost:3000/Elkin/Challenge/FirstChallenge/
- **Database**: `phpaccountant`
- **Test Users**:
  - Username: `elkin` | Password: (any)
  - Username: `david` | Password: (any)

### ThirdChallenge - Pattern Memory Game

- **URL**: http://localhost:3000/Elkin/Challenge/ThirdChallenge/challenge3.php
- **Database**: `thirdchallenge`
- **Sample Scores**: Elkin (9750 points) and David (9650 points)

### phpMyAdmin - Database Administration

- **URL**: http://localhost:8080
- **Username**: `root`
- **Password**: `root`
- **Purpose**: Manage databases, run SQL queries, view tables

---

## Database Information

### phpaccountant (FirstChallenge)

**Tables:**
- `users` - User accounts and authentication
- `salary_records` - Salary calculation records
- `salary_records_details` - Shift details (date, start time, end time)

**Sample Data:**
- 2 pre-created users (elkin, david)
- 2 sample salary records

### thirdchallenge (ThirdChallenge)

**Table:**
- `pattern_scores` - Game scores and times

**Sample Data:**
- Elkin: 9750 points in 5 seconds
- David: 9650 points in 7 seconds

---

## Useful Docker Commands

### View container status and logs

```powershell
# See all containers and their status
docker-compose ps

# View live logs of all containers
docker-compose logs -f

# View logs of specific service (MySQL, PHP, etc.)
docker-compose logs -f mysql
docker-compose logs -f first_steps_php
docker-compose logs -f phpmyadmin

# View last 50 lines of logs
docker-compose logs --tail=50
```

### Manage containers

```powershell
# Restart containers (keeps data)
docker-compose restart

# Restart specific container
docker-compose restart mysql

# Stop containers (keeps data - databases persist)
docker-compose down

# Stop and remove everything including data (full reset)
docker-compose down -v

# Rebuild and restart (if Dockerfile changed)
docker-compose up --build
```

### Database operations

```powershell
# Access MySQL command line
docker exec -it mysql mysql -u root -p
# When prompted, password is: root

# Execute SQL file in container
docker exec -i mysql mysql -u root -proot < filename.sql

# Backup database
docker exec mysql mysqldump -u root -proot phpaccountant > backup.sql
```

### System cleanup

```powershell
# Remove unused Docker images and containers
docker system prune -f

# Remove unused volumes
docker volume prune -f

# Full cleanup (WARNING: removes everything)
docker system prune -a -f
```

---

## Troubleshooting

### Issue: "Unknown database 'phpaccountant'"

**Cause:** SQL initialization scripts didn't run properly.

**Solution:**
```powershell
docker-compose down -v
docker system prune -f
docker-compose up --build -d
Start-Sleep -Seconds 15
docker-compose logs mysql
```

Verify you see the "Creating database phpaccountant" message.

---

### Issue: "Port 3000 already in use" or "Port 8080 already in use"

**Cause:** Another service is using those ports.

**Solution 1:** Stop the conflicting service and try again.

**Solution 2:** Change ports in `docker-compose.yml`:
```yaml
php-apache:
  ports:
    - "3001:80"  # Changed from 3000:80
    
phpmyadmin:
  ports:
    - "8081:80"  # Changed from 8080:80
```

Then restart:
```powershell
docker-compose down
docker-compose up -d
```

---

### Issue: "MySQL connection timeout" or connection refused

**Cause:** MySQL is still initializing or not responding.

**Solution:**
```powershell
# Wait a bit more
Start-Sleep -Seconds 15

# Check MySQL logs
docker-compose logs mysql

# If still not ready, restart
docker-compose restart mysql

# Wait and check again
Start-Sleep -Seconds 10
docker-compose logs mysql | Select-Object -Last 10
```

Look for message: `ready for connections`

---

### Issue: "Composer install failed" or PHP errors

**Cause:** Dependencies weren't installed properly.

**Solution:**
```powershell
docker-compose down -v
docker-compose up --build -d
```

The rebuild will reinstall all dependencies.

---

### Issue: Can't access http://localhost:3000

**Cause:** PHP container might not be ready yet.

**Solution:**
```powershell
# Check if container is running
docker-compose ps

# View PHP logs
docker-compose logs first_steps_php

# Restart PHP container
docker-compose restart first_steps_php

# Wait 10 seconds and try again
Start-Sleep -Seconds 10
```

---

## Project Files Related to Docker

### Configuration Files

1. **`Dockerfile`** - Defines the PHP/Apache Docker image
   - Uses PHP 8.1.33 with Apache
   - Installs MySQL extensions (mysqli, PDO)
   - Installs Composer for dependency management
   - Enables Apache rewrite module

2. **`docker-compose.yml`** - Defines all services and their configuration
   - **php-apache service**: Web server on port 3000
   - **mysql service**: Database on port 3307
   - **phpmyadmin service**: DB admin on port 8080
   - **Volumes**: Maps source code and initialization scripts

3. **`docker-entrypoint-initdb.d/`** - SQL initialization scripts
   - `01-init.sql` - Creates phpaccountant database
   - `02-init-thirdchallenge.sql` - Creates thirdchallenge database

### Database Configuration

The applications are configured to use Docker's MySQL service:

- **Host**: `mysql` (Docker service name, not localhost)
- **Port**: `3306` (internal Docker port)
- **User**: `root`
- **Password**: `root`

These are configured in:
- `Elkin/Challenge/FirstChallenge/config/app.json`
- `Elkin/Challenge/ThirdChallenge/config/app.json`
- `Elkin/stage6/config/app.json`

---

## For Team Members (After Git Pull)

1. **After pulling new changes**, run:
   ```powershell
   docker-compose down -v
   docker-compose up --build -d
   ```

2. **Wait for MySQL to initialize** (2-3 minutes):
   ```powershell
   Start-Sleep -Seconds 15
   docker-compose logs mysql
   ```
