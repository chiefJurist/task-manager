```
# Task Manager

A task management app with drag-and-drop reordering and project filtering. Built as a Laravel API + Vue SPA.

## Features

Create, edit, and delete tasks. Drag-and-drop reordering updates task priority automatically based on order. Tasks can be filtered by project using a dropdown. Login and registration are handled through Laravel Sanctum.

## Tech Stack

The backend is built with PHP 8.3, Laravel 11, and MySQL. The frontend is built with Vue 3, Tailwind CSS, and Vite. Authentication uses Laravel Sanctum with SPA cookie auth.

## Project Structure

task-manager/
- task-manager-api/ — Laravel API
- task-manager-spa/ — Vue frontend

## Setup

### Requirements

You will need PHP 8.3 or higher with Composer, Node.js 18 or higher with npm, and MySQL installed.

### 1. Backend (task-manager-api)

Move into the backend folder and install dependencies:

cd task-manager-api
composer install
cp .env.example .env
php artisan key:generate

Set your database credentials in .env:

DB_DATABASE=task_manager_api
DB_USERNAME=root
DB_PASSWORD=your_password_here

Also confirm these values match your frontend's URL:

APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173

Create the database and run migrations:

mysql -u root -p -e "CREATE DATABASE task_manager_api"
php artisan migrate --seed

Start the server:

php artisan serve

This runs the API at http://localhost:8000.

### 2. Frontend (task-manager-spa)

Move into the frontend folder and install dependencies:

cd task-manager-spa
npm install
cp .env.example .env

Set the API URL in .env:

VITE_API_URL=http://localhost:8000

Start the dev server:

npm run dev

This runs the frontend at http://localhost:5173.

If port 5173 is already in use, Vite will pick another port. If that happens, update FRONTEND_URL and SANCTUM_STATEFUL_DOMAINS in the API's .env to match the new port, then restart php artisan serve.

### 3. Using the App

Open http://localhost:5173 in your browser, register or log in, then create, edit, delete, and reorder tasks. Use the project dropdown to filter the task list.

## API Routes

Auth routes: POST /api/register to register, POST /api/login to log in, POST /api/logout to log out, and GET /api/user to fetch the current user.

Project routes: GET and POST on /api/projects to list or create projects, and PUT or DELETE on /api/projects/{id} to update or delete one.

Task routes: GET and POST on /api/tasks to list or create tasks, PUT or DELETE on /api/tasks/{id} to update or delete one, and POST /api/tasks/reorder to update task priorities after a drag-and-drop reorder.

## Deployment

For the API, deploy it like any Laravel app, for example with Forge or a VPS running nginx and php-fpm. Set APP_ENV=production and APP_DEBUG=false, run php artisan config:cache route:cache view:cache, and point FRONTEND_URL and SANCTUM_STATEFUL_DOMAINS at your production frontend domain.

For the frontend, run npm run build to generate a static dist/ folder, then serve it from any static host such as Netlify, Vercel, S3, or nginx. Set VITE_API_URL to your production API URL at build time.

## Testing

cd task-manager-api
php artisan test
```