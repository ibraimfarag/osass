# Price List System

a laravel-based e-commerce pricing system that handles pricing based on country, currency, and time periods.

## 📌 Project Overview

This system allows:
- price lists for products
- Country/currency-specific pricing
- Time-based promotional pricing
- Automatic price selection based on:
  - User's location (country)
  - Preferred currency
  - Current date
  - Priority rules

## Features

- **Pricing**
  - Country-specific prices
  - Currency-specific prices
  - Date-range limited promotions
  
- **Priority System**
  - Multiple matching price lists
  - Lower priority number = higher precedence

- **Fallback Mechanism**
  - Automatic fallback to base price
  - Graceful handling of missing data

- **REST API**
  - Product listing with dynamic pricing
  - Single product detail endpoint
  - Price-based sorting (bonus feature)

## 🗄 Database Schema

### Tables
```sql
products
- id
- name
- base_price
- description
- timestamps

price_lists
- id
- product_id
- country_code (nullable)
- currency_code (nullable)
- price
- start_date
- end_date
- priority
- timestamps

countries
- id
- code (unique)
- name

currencies
- id
- code (unique)
- name
