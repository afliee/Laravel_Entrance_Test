
# Laravel API

This repository contains a Laravel RESTful API application, which is dockerized for easy local development. The setup uses Docker and Docker Compose to manage services such as Nginx, PostgreSQL, and PHP-FPM.

## Prerequisites

Before you begin, ensure you have the following installed on your machine:
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Getting Started

Follow these instructions to get the Laravel API up and running locally.

### 1. Clone the Repository

```bash
git clone https://github.com/afliee/Laravel_Entrance_Test.git
cd Laravel_Entrance_Test
```

### 2. Set Up Environment Variables

Copy the `.env.example` file to create your `.env` file:

```bash
cp .env.example .env
```

Edit the `.env` file and configure the following variables according to your setup:
- `DB_CONNECTION=pgsql`
- `DB_HOST=postgres`
- `DB_PORT=5432`
- `DB_DATABASE=laravel_db`
- `DB_USERNAME=postgres`
- `DB_PASSWORD=your_password`
- `APP_URL=http://localhost`

> Note: Ensure the database credentials match what you will define in the `docker-compose.yml` file.

### 3. Build and Run Docker Containers

Run the following command to build and start the Docker containers for Nginx, PostgreSQL, and PHP-FPM:

```bash
docker-compose up -d --build
```

This command will:
- Build the images if they don't already exist.
- Start the containers in detached mode.

### 4. Install Composer Dependencies

After the containers are up, you need to install the PHP dependencies using Composer:

```bash
docker-compose exec app composer install
```

### 5. Generate Application Key

Run the following command to generate the Laravel application key:

```bash
docker-compose exec app php artisan key:generate
```

### 6. Run Database Migrations

Run the migrations to set up the database schema:

```bash
docker-compose exec app php artisan migrate
```

### 7. Access the Application

You can now access the Laravel API locally by visiting [http://localhost](http://localhost).

## Running Docker Commands

Here are some useful commands for managing your Docker setup.

### Stop Containers

To stop the running containers:

```bash
docker-compose down
```

### View Logs

To view the logs for a specific container:

```bash
docker-compose logs <service_name>
```

For example, to view Nginx logs:

```bash
docker-compose logs nginx
```

### Accessing the App Container

To run commands within the app container, such as Artisan commands or shell access, use the following:

```bash
docker-compose exec app bash
```

## File Structure

Here’s a quick overview of the file structure related to Docker:

```bash
├── docker-compose.yml        # Docker Compose configuration
├── Dockerfile                # Dockerfile for building the app container
├── nginx.conf            # Nginx configuration
├── .env                      # Environment variables for Laravel and Docker
├── .env.example              # Example environment configuration
└── ...
```
