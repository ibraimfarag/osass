# 🏷️ Price List System - E-commerce Pricing API  

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)  
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)  
[![PHP](https://img.shields.io/badge/PHP->=8.1-blue?style=flat&logo=php)](https://www.php.net/)  
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)  

A **dynamic pricing system** for e-commerce platforms that supports **country-specific pricing, currency conversion, and time-based promotions**.  

---

## 🚀 Getting Started  

### 📌 Prerequisites  
- PHP **≥ 8.1**  
- Composer **2**  
- MySQL / MariaDB  

### 📥 Installation  

#### 1️⃣ Clone the repository  
```bash
git clone https://github.com/ibraimfarag/osass.git -b DEVELOPMENT 
cd osass
```

#### 2️⃣ Install dependencies  
```bash
composer install
```

#### 3️⃣ Configure environment  
```bash
cp .env.example .env
php artisan key:generate
```
👉 Update `.env` file with your **database credentials**:  
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=price_list
DB_USERNAME=root
DB_PASSWORD=
```

#### 4️⃣ Run migrations & seed data  
```bash
php artisan migrate --seed
```

#### 5️⃣ Start development server  
```bash
php artisan serve
```

#### 6️⃣ Access API endpoints  
```
http://localhost:8000/api/products
```

---

## 📡 API Endpoints  

### 🛍 Products  
| Method | Endpoint | Description |  
|--------|---------|-------------|  
| **GET** | `/products` | List all products with pricing |  
| **POST** | `/products` | Create new product |  
| **GET** | `/products/{id}` | Get single product with pricing |  
| **PUT** | `/products/{id}` | Update product |  
| **DELETE** | `/products/{id}` | Delete product |  

### 📌 Example Request  
```bash
curl "http://localhost:8000/api/products?country_code=US&currency_code=USD&date=2024-06-15"
```

### 📌 Sample Response  
```json
{
  "data": [
    {
      "id": 1,
      "name": "Premium Widget",
      "base_price": 199.99,
      "applicable_price": 179.99,
      "currency": "USD",
      "conversion_rate": 1.0
    }
  ]
}
```

---

## 🧠 Business Logic  

### 🔄 Price Resolution Flow  
1️⃣ **Check for matching price lists in this order:**  
   - **Country + Currency + Date Range**  
   - **Country + Date Range**  
   - **Currency + Date Range**  
   - **Global Price** (no country/currency)  

2️⃣ **Select the price list with the highest priority** (lowest number).  

3️⃣ **Fallback to product base price** if no matches are found.  

---

### 💱 Currency Conversion  
Formula:  
```php
converted_price = applicable_price × (target_currency_rate / base_currency_rate)
```

---

## 🗄 Database Schema  

### 📌 **Products Table**  
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->decimal('base_price', 10, 2);
    $table->text('description')->nullable();
    $table->timestamps();
});
```

### 📌 **Price Lists Table**  
```php
Schema::create('price_lists', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained();
    $table->string('country_code', 2)->nullable();
    $table->string('currency_code', 3)->nullable();
    $table->decimal('price', 10, 2);
    $table->date('start_date');
    $table->date('end_date');
    $table->integer('priority');
    $table->timestamps();
});
```

---

## 🤔 Assumptions & Decisions  

### 📅 **Date Handling**  
✔️ All dates use **server timezone (UTC)**  
✔️ Date ranges are **inclusive** (`start_date <= date <= end_date`)  

### 💰 **Currency Management**  
✔️ Only **one currency** can be marked as the **base currency**  
✔️ Exchange rates are stored **with 4 decimal places**  
✔️ **Manual exchange rate updates** (no automatic sync)  

### 🔄 **Fallback Logic**  
✔️ `NULL` country_code = valid for **all countries**  
✔️ `NULL` currency_code = valid for **all currencies**  
✔️ Missing price list = **fallback to base price**  

### ✅ **Validation Rules**  
✔️ Country codes: **ISO 3166-1 alpha-2** (2 letters)  
✔️ Currency codes: **ISO 4217** (3 letters)  
✔️ Priority: **Lower numbers = higher precedence**  

### ⚡ **Performance Enhancements**  
✔️ **Eager loading of relationships**  
✔️ **Database indexes on critical columns:**  
   - `price_lists(country_code, currency_code)`  
   - `price_lists(start_date, end_date)`  
   - `currencies(is_base)`  

---

## 🐛 Error Handling  

### 🚨 Sample Error Responses  

#### 🔴 **400 Bad Request:**  
```json
{
  "message": "Invalid date format",
  "errors": {
    "date": ["The date does not match the format Y-m-d."]
  }
}
```

#### 🔴 **404 Not Found:**  
```json
{
  "message": "No query results for model [App\Models\Product] 99"
}
```

---




## 📜 License  

This project is **licensed under the MIT License** - see the [LICENSE](LICENSE) file for details.  



## 📬 Contact  

💻 **Author:** [Ibrahim Ahmed](https://github.com/ibraimfarag)  
📧 **Email:** [ib.farag@gmail.com](mailto:ib.farag@gmail.com)  

---

### ⭐ If you like this project, give it a star! ⭐  
