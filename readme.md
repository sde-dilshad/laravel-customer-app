**how to initialize this project inside Docker**, covering all the key Laravel and Passport commands, permissions setup, and running tests — all as step-by-step commands only:

---

## Notes
* Ensure your `.env` file is correctly configured with database credentials matching your Docker setup. ()
* If you run into permission errors, double-check the permissions on `storage` and `bootstrap/cache`.

````markdown
# Project Initialization Guide

Follow these steps to set up the Laravel project running in Docker.
````
---

## 1. Clone the Repository

First, clone the project repository from GitHub (or your Git remote):

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo

---

## 2: Start Docker Containers

Make sure your Docker and Docker Compose are installed and working.

Start your containers:

```bash
docker-compose up -d --build
````

---

## Step 2: Set Permissions

Set correct permissions for Laravel directories:

```bash
docker exec -it laravel-app bash
chmod -R 777 storage bootstrap/cache
```

If `storage` or `bootstrap/cache` folders don't exist, create them first:

```bash
mkdir -p storage framework/cache framework/sessions framework/views bootstrap/cache
chmod -R 777 storage bootstrap/cache
```

---

## Step 3: Run Migrations

Run the database migrations inside the container:

```bash
docker exec -it laravel-app php artisan migrate
```

---

## Step 4: Install Laravel Passport

Run the following commands inside the container to set up Passport:

```bash
docker exec -it laravel-app php artisan passport:install
```

Create clients for different grant types (choose what your app needs):

* Personal Access Client:

```bash
docker exec -it laravel-app php artisan passport:client --personal
```

* Password Grant Client:

```bash
docker exec -it laravel-app php artisan passport:client --password
```

* Authorization Code or other clients:

```bash
docker exec -it laravel-app php artisan passport:client
```

---

## Step 5: Generate App Key

```bash
docker exec -it laravel-app php artisan key:generate
```

---

## Step 6: Run Tests

To run your PHPUnit tests inside Docker:

```bash
docker exec -it laravel-app ./vendor/bin/phpunit
```

To run your API testcases tests inside Docker:

```bash
docker exec -it laravel-app php artisan test
```

---


---

This setup will get your Laravel app ready with Passport authentication inside Docker and allow you to run your tests smoothly.
