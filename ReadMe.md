# Task Manager

A task management app with drag-and-drop reordering and project filtering. Built as a Laravel API and a Vue SPA.

## Project Structure

task-manager/
- task-manager-api/ (Laravel API)
- task-manager-spa/ (Vue frontend)

## Features

- Create, edit, update and delete tasks and projects. 
- Drag-and-drop, reordering task priority automatically based on a priority column. 
- Tasks can be filtered by project using a dropdown. 
- Login and registration are handled through Laravel Sanctum.

## Tech Stack

- The backend is built with PHP 8.3, Laravel 13 and MySQL.
- The frontend is built with Vue 3, Tailwind CSS and Vite.
- Authentication uses Laravel Sanctum with SPA cookie auth.

## Setup

### Requirements

You will need PHP 8.3 or higher with Composer, Node.js 18 or higher with npm and MySQL installed.

### 1. Backend (task-manager-api)

a). MOVE INTO THE BACKEND FOLDER AND INSTALL DEPENDENCIES:
- cd task-manager-api
- composer install
- cp .env.example .env
- php artisan key:generate

b). SET YOUR DATABASE CREDENTIALS IN .env:
```
DB_DATABASE=task_manager_api
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

c). ALSO CONFIRM THESE VALUES MATCH YOUR FRONTEND'S URL:
```
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173
```

d). CREATE THE DATABASE AND RUN MIGRATIONS:
- php artisan migrate --seed

This would create three user accounts with tasks and projects. One of the user has a predicable email and password and the other two are unpredictable emails and the same predictable password (password). Below are the credentials.
- email: anthonynnanna@ct.com
- password: password

e). START THE SERVER:
- php artisan serve
OR
- composer run dev. 

This runs the API at http://localhost:8000.

Startup other backend services if you have other methods of starting your MYSQL or PHPMyAdmin.


### 2. Frontend (task-manager-spa)

a). MOVE INTO THE FRONTEND FOLDER AND INSTALL DEPENDENCIES:
- cd task-manager-spa
- npm install

b). SET THE baseURL IN src/lib/axios.js:
```
baseURL: 'http://localhost:8000'
```

c). START THE DEV SERVER:
- npm run dev

This runs the frontend at http://localhost:5173.

If port 5173 is already in use, Vite will pick another port. If that happens, update FRONTEND_URL and SANCTUM_STATEFUL_DOMAINS in the API's .env to match the new port, then restart php artisan serve.

### 3. Using the App

Open http://localhost:5173 in your browser, register or log in, then create, edit, delete, update and reorder tasks and projects. Use the project dropdown to filter the task list.

## Deployment

For the API, deploy it like any Laravel app, for example with Forge or a VPS running nginx and php-fpm. Set APP_ENV=production and APP_DEBUG=false, run php artisan config:cache route:cache view:cache and point FRONTEND_URL and SANCTUM_STATEFUL_DOMAINS at your production frontend domain.

For the frontend, run npm run build to generate a static dist/ folder, then serve it from any static host such as Netlify, Vercel, S3 or nginx. Set axios.js baseURL to your production API URL at build time.