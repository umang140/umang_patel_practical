# Tech Stack

- PHP 8.2+
- Laravel 12
- MySQL
- Laravel Sanctum
- Laravel Queue
- Database Queue Driver

# Project Setup

1. Clone Repository

git clone https://github.com/umang140/umang_patel_practical.git

2. Install Composer Dependencies

composer install

3. Create Environment File

.env.example to .env

4. Configure Database

Update `.env` file:

```env
APP_NAME="Laravel Product Import API"

APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_name
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database

5. Run Migrations

```bash
php artisan migrate
```

---

6. Create Queue Jobs Table


php artisan queue:table


Run migration again:


php artisan migrate

# Running The Application

## Start Laravel Development Server

```bash
php artisan serve
```

Application URL:

```text
http://127.0.0.1:8000
```

Start queue worker in a separate terminal:

```bash
php artisan queue:work 
```

Queue worker must remain running while importing CSV files.

# Running Tests

php artisan test

# Authentication

Authentication is handled using Laravel Sanctum.

Protected APIs require Bearer Token authentication.

Example header:

Authorization: Bearer YOUR_TOKEN

# API Endpoints

# Authentication APIs

## Register User

### Endpoint

```http
POST /api/register
```

### Request Body

```json
{
    "name": "Umang",
    "email": "umang@test.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### Curl Example

```bash
curl --location 'http://127.0.0.1:8000/api/register' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "Umang",
    "email": "umang@test.com",
    "password": "password123",
    "password_confirmation": "password123"
}'
```
## Login User

### Endpoint

```http
POST /api/login
```

### Request Body

```json
{
    "email": "umang@test.com",
    "password": "password123"
}
```

### Curl Example

```bash
curl --location 'http://127.0.0.1:8000/api/login' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email": "umang@test.com",
    "password": "password123"
}'
```
# Product APIs

All product APIs require Bearer Token authentication.

## Create Product

### Endpoint

```http
POST /api/products
```

### Request Body

```json
{
    "name": "Pen",
    "sku": "PEN-001",
    "price": 10,
    "stock": 50,
    "category": "Stationery",
    "status": "active"
}
```

### Curl Example

```bash
curl --location 'http://127.0.0.1:8000/api/products' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "Pen",
    "sku": "PEN-001",
    "price": 10,
    "stock": 50,
    "category": "Stationery",
    "status": "active"
}'
```

## Product Listing

### Endpoint

```http
GET /api/products
```

### Curl Example

```bash
curl --location 'http://127.0.0.1:8000/api/products' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN'
```

## Get Single Product

### Endpoint

```http
GET /api/products/{id}
```

### Curl Example

```bash
curl --location 'http://127.0.0.1:8000/api/products/1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN'
```
## Delete Product

### Endpoint

```http
DELETE /api/products/{id}
```

### Curl Example

```bash
curl --location --request DELETE 'http://127.0.0.1:8000/api/products/1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN'
```

# CSV Import APIs

---

## Import Products CSV

### Endpoint

```http
POST /api/products/import
```

### Request Type

```text
multipart/form-data
```

### Form Data

| Key | Type |
|-----|------|
| file | File |

### Curl Example

```bash
curl --location 'http://127.0.0.1:8000/api/products/import' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN' \
--form 'file=@"/path/products.csv"'
```

---

## Check Import Status

### Endpoint

```http
GET /api/imports/{id}
```

### Curl Example

```bash
curl --location 'http://127.0.0.1:8000/api/imports/1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN'
```

# Sample CSV File

Sample CSV file is available at:

```text
samples/products.csv
```

The sample file contains:

- Valid product rows
- Invalid duplicate SKU row
- Invalid negative value rows

# Queue Processing

CSV imports are processed in background jobs using Laravel Queue.

Queue worker command:

```bash
php artisan queue:work
```

---
