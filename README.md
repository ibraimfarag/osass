# Price List System

An e-commerce pricing system that handles country/currency-specific pricing with time-based promotions.

## 📋 Table of Contents
- [Features](#-features)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [API Documentation](#-api-documentation)
- [Assumptions & Decisions](#-assumptions--decisions)
- [Future Improvements](#-future-improvements)
- [Testing](#-testing)

## 🚀 Features
- Multi-dimensional pricing (country/currency/date)
- Priority-based price selection
- Fallback to base pricing
- Date-range promotions
- Ordering by price (bonus)

## 🛠 Prerequisites
- PHP 8.1+
- Composer 2.5+
- MySQL 8.0+
- Laravel 10+


## 📥 Installation

### 1️⃣ Clone Repository
```bash
git clone https://github.com/ibraimfarag/osass -b main
cd osass
```

### 2️⃣ Install Dependencies
```bash
composer install
```

### 3️⃣ Configure Environment
```bash
cp .env.example .env
```
Update `.env` with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=price_list
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Generate Key
```bash
php artisan key:generate
```

### 5️⃣ Run Migrations & Seeders
```bash
php artisan migrate --seed
```

### 6️⃣ Start Server
```bash
php artisan serve
```

## 📚 API Documentation

### Endpoints

#### 1️⃣ List Products  
**GET** `/api/products`
```bash
curl http://localhost:8000/api/products?country_code=US&currency_code=USD&date=2024-06-15&order=lowest-to-highest
```

#### 2️⃣ Get Single Product  
**GET** `/api/products/{id}`
```bash
curl http://localhost:8000/api/products/1?country_code=DE&currency_code=EUR&date=2024-12-01
```

### Parameters

| Parameter      | Description                          | Example        |
|---------------|--------------------------------------|----------------|
| `country_code` | 2-letter ISO country code           | US, DE, CA     |
| `currency_code` | 3-letter ISO currency code          | USD, EUR, CAD  |
| `date`         | Date in YYYY-MM-DD format           | 2024-06-15     |
| `order`        | Sorting: `lowest-to-highest` or `highest-to-lowest` | |

## 🧠 Assumptions & Decisions

### Database Design
1. **Null Handling**:  
   - `country_code`/`currency_code` as nullable = global applicability  
   - Indexed null columns for faster queries  

2. **Priority System**:  
   - Lower priority numbers = higher precedence  
   - Unique index on `(product_id, country_code, currency_code, priority)`

3. **Date Handling**:  
   - Server timezone used for date comparisons  
   - Inclusive date ranges (`<=` and `>=`)

### Business Logic
1. **Fallback Mechanism**:  
   - Base price used when no matching price lists  
   - No currency conversion - assumes pre-converted prices  

2. **Query Optimization**:  
   - Eager loading of price lists  
   - Composite index on `(country_code, currency_code, start_date, end_date)`

3. **Validation**:  
   - Strict 2/3 character codes validation  
   - ISO 8601 date format enforcement  

### API Design
1. **Parameter Handling**:  
   - Current date used when not specified  
   - All parameters optional  
   - Meta data in responses for debugging  

2. **Error Handling**:  
   - `422` status for invalid parameters  
   - `404` for missing resources  
   - Clear error messages in JSON format  


### Sample Test Data
- 3 Countries: `US`, `CA`, `DE`
- 3 Currencies: `USD`, `CAD`, `EUR`
- 5 Sample Products
- 10 Price List Entries

---

**Note**: Always run in a development environment. For production:
- Set `APP_ENV=production`
- Configure proper database backups
- Implement rate limiting
- Use HTTPS
