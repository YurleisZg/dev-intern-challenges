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
php artisan migrate
```
This will automatically fill APP_KEY in your .env.

## Start the development server
If you want to run de front-end run the next command in client folder. 

```bash
cd learning-dashboard
php artisan serve
```

## Notes
After changing `.env`, run
```bash
cd learning-dashboard
php artisan config:clear
```
