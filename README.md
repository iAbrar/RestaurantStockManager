
# Laravel Dockerized Restaurant Stock Manager 🍔📦

## ✨ Introduction
Welcome to the Laravel Dockerized Restaurant Stock Manager! This system, designed with Laravel and Docker, is engineered to streamline inventory management and order processing for a burger restaurant. It features robust handling of `Products`, `Ingredients`, and `Orders`.

### 🌟 System Overview
- **Products (e.g., Burgers):** Composed of various ingredients.
- **Ingredients:** Essential items (Beef, Cheese, Onion) with tracked inventory.
- **Orders:** Customer orders that dynamically adjust ingredient stock.

🔍 Each customer order updates ingredient stock levels in real-time. Additionally, a stock level dip below 50% triggers an automated email alert, prompting inventory replenishment.

## 📋 Prerequisites

Before diving in, make sure Docker is up and running on your system. If not, here's how you can set it up:

### 💻 Installing Docker

#### For Windows and macOS:

- Download Docker Desktop from [Docker Desktop](https://www.docker.com/products/docker-desktop).
- Follow the installation guide and launch Docker Desktop upon completion.

#### For Linux:

- Detailed installation guides per distribution are available at [Docker Engine](https://docs.docker.com/engine/install/).

#### ✅ Verify Your Installation:

Run these commands to confirm Docker is installed:

```bash
docker --version
docker-compose --version
```

## 🔧 Getting Started

### Step 1: Get the Code 🐙

```bash
git clone https://github.com/iAbrar/RestaurantStockManager.git
cd RestaurantStockManager
```

### Step 2: Set Your Stage ⚙️

```bash
cp .env.example .env
# Tailor the .env file to your environment
```

### Step 3: Ignite Docker 🐳

```bash
docker-compose up -d --build
```

## 🚀 Let's Roll!

### 🌐 Access the Application
Head over to `http://localhost` to see the application in action.

### 🚧 Running Migrations
Set up your database schema:

```bash
docker-compose run --rm artisan migrate
```

### 🌱 Seeding the Database
Kickstart your database with initial stock:

```bash
docker-compose run --rm artisan db:seed --class=IngredientSeeder
```

## 📧 Setting Up Email Notifications with Mailtrap

For handling user notifications, this project is configured to work with mail services like Mailtrap. Mailtrap provides a safe testing environment for emails during development.

### Setting Up Mailtrap:

1. Create a free account on [Mailtrap](https://mailtrap.io/).
2. Find or create a 'demo inbox' and note down the SMTP settings.
3. Update your `.env` file with Mailtrap's SMTP settings.
4. With these settings, emails sent by the application will be captured by Mailtrap.

## 🐳 Docker Configuration Details

This project uses Docker for easy setup and management. The Docker configuration includes services for Nginx, PHP, and Composer.

### Nginx
- **Dockerfile:** Located at `nginx.dockerfile`.
- **Configuration:** Configured to serve the Laravel application.

### PHP
- **Dockerfile:** Located at `php.dockerfile`.
- **Configuration:** Handles PHP processing for the application.

### Composer
- **Dockerfile:** Located at `composer.dockerfile`.

- **Usage** Used to manage PHP dependencies.
- **Configuration:** Run Composer commands using Docker:
  ```bash
  docker-compose run --rm composer [command]
  ```

### 🎯 Core Features

#### 🛒 Order Processing

##### Endpoint: `POST /api/orders`
Processes customer orders and updates inventory.

##### Request Payload Structure:
```json
{
  "products": [
    {
      "product_id": [Product ID],
      "quantity": [Quantity Ordered]
    }
  ]
}
```

#### 📉 Stock Management and Alerts
Automatically updates stocks and sends alerts for low inventory.

## 🧪 Testing

Run the test suite:
```bash
docker-compose run --rm artisan test
```

## 📝 Note to Users

- **Prototype Nature:** This system is a prototype, focusing on key functionalities.
- **Simplified Customer Model:** No detailed customer model linked to orders.
- **Basic Order Info:** Orders don't include pricing or detailed commercial data.
- **Admin Dashboard:** Dashboard for order management is on our roadmap!
- **Extensibility:** Lays groundwork for future enhancements.

