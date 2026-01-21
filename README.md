# Project README

This repository contains a **Laravel** application.  
Follow the steps below to install dependencies, configure the environment, create the database, run migrations, and start the project locally.

## Requirements

Make sure you have:

- **PHP** 
- **Composer**
- **Node.js + npm** 
- **Database**: MariaDB 
- **Git**

## 📂 Project Structure

The project is organized into a root Docker configuration and specific user folders containing the exercises.

```text
├── Archive                  
├── learning-dashboard              
├── Requirements    
```

## Configure environment variables

First, go into the project folder:
```bash
cd learning-dashboard
```

Then, copy the template file and rename it to `.env` and set the environment variables:

- `DB_CONNECTION` = database driver

- `DB_HOST` = database host (usually 127.0.0.1 or localhost)

- `DB_PORT` = database port

- `DB_DATABASE` = database name

- `DB_USERNAME` = database user

- `DB_PASSWORD` = database password 

## Generate the APP_KEY

Laravel needs an application key for encryption:
```bash
cd learning-dashboard
php artisan key:generate
```
This will automatically fill APP_KEY in your .env.

## Run migrations

```bash
cd learning-dashboard
php artisan migrate:fresh
```
This will automatically fill APP_KEY in your .env.

## Start the development server
If you want to run de front-end run the next command in client folder. 

```bash
cd learning-dashboard
php artisan serve
```

## 🐳 Running with Docker

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop) installed and running
- The project code downloaded/cloned

### Step-by-Step Instructions

#### Step 1: Navigate to the project folder

Open a terminal and go to the project directory:

```bash
cd learning-dashboard
```

#### Step 2: Create `.env` configuration file

Create a `.env` file in the project root with the following content:

```dotenv
APP_NAME=LearningDashboard
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=learning_dashboard
DB_USERNAME=laravel
DB_PASSWORD=secret
DB_ROOT_PASSWORD=root

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=null

MAIL_MAILER=log
```

#### Step 3: Download and start the containers

```bash
docker-compose up -d
```

⏳ This may take 5-10 minutes on first run. Wait for completion.

Verify everything is running:
```bash
docker-compose ps
```

You should see 4 containers running:
- `laravel-app` (PHP)
- `laravel-nginx` (Web server)
- `laravel-mariadb` (Database)
- `laravel-redis` (Cache)

#### Step 4: Generate the application key

```bash
docker-compose exec app php artisan key:generate
```

✅ You should see: `Application key set successfully`

#### Step 5: Run migrations

```bash
docker-compose exec app php artisan migrate
```


#### Step 6: Access the application

Open your browser and go to:

```
http://localhost:8080
```

### Default Configuration

| Variable | Value |
|----------|-------|
| **Database name** | `learning_dashboard` |
| **Database user** | `laravel` |
| **Database password** | `secret` |
| **Application URL** | `http://localhost:8080` |
| **Web server port** | 8080 |
| **Database port** | 3306 |
| **Redis port** | 6379 |

### Common Issues and Solutions

#### ❌ "docker-compose: command not found"
Make sure Docker Desktop is installed and running.

#### ❌ Ports 8080, 3306, or 6379 already in use
Edit `docker-compose.yml` and change the port mappings:
```yaml
nginx:
  ports:
    - "8081:80"  # Change 8080 to 8081
```
Then access `http://localhost:8081`

#### ❌ Application won't load (blank page)
```bash
docker-compose down -v
docker-compose up -d --build
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

#### ❌ Permission errors in files
```bash
docker-compose exec app chmod -R 755 storage bootstrap/cache
```

#### ❌ Database issues
```bash
docker-compose exec mariadb mysql -u root -proot
# Inside MySQL:
SHOW DATABASES;
USE learning_dashboard;
SHOW TABLES;
exit;
```

## Notes
After changing `.env`, run
```bash
cd learning-dashboard
php artisan config:clear
```
