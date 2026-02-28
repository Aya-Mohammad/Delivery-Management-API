# Delivery Management API

A RESTful backend API for managing delivery operations, built with **Laravel** and **Laravel Sanctum** authentication.

## 🚀 Technologies Used

- PHP ^8.1  
- Laravel Framework (API backend)  
- Laravel Sanctum for authentication  
- MySQL (or any relational DB)

## 📦 Features

### 🔐 Authentication
- Login via phone number & email  
- Register new user  
- Verify & resend verification code  
- Password reset  

### 👤 User Profile
- Update user info  
- Manage favourites  
- Search users  
- Add driver  

### 🛍️ Entities
- Shops
- Categories
- Products
- Orders

---

## 🛠 Installation

1. Clone the repository

```bash
git clone https://github.com/Aya-Mohammad/Delivery-Management-API.git
cd Delivery-Management-API/Back
````

2. Install dependencies

```bash
composer install
```

3. Copy `.env.example` to `.env`

```bash
cp .env.example .env
```

4. Configure your database in `.env`

5. Generate app key

```bash
php artisan key:generate
```

6. Run migrations

```bash
php artisan migrate
```

7. Start the development server

```bash
php artisan serve
```

---

## 📄 API Documentation

### Authentication

| Method | Endpoint                 | Description                     |
| ------ | ------------------------ | ------------------------------- |
| POST   | /api/number_login        | Login with phone                |
| POST   | /api/email_login         | Login with email                |
| POST   | /api/register            | Register new user               |
| POST   | /api/logout              | Logout (requires auth)          |
| POST   | /api/verify              | Verify code (auth)              |
| POST   | /api/resend              | Resend verification code (auth) |
| POST   | /api/reset_password_code | Request password reset code     |
| POST   | /api/reset_password      | Reset password                  |

### User Routes (protected)

| Method | Endpoint                    | Description      |
| ------ | --------------------------- | ---------------- |
| POST   | /api/user/update            | Update user info |
| POST   | /api/user/favourites/add    | Add favourite    |
| POST   | /api/user/favourites/remove | Remove favourite |
| POST   | /api/user/search            | Search users     |
| POST   | /api/user/addDriver         | Add driver       |

### Shops

| Method | Endpoint         | Description         |
| ------ | ---------------- | ------------------- |
| POST   | /api/shops       | List shops          |
| POST   | /api/shops/show  | Show shop details   |
| POST   | /api/shops/store | Add new shop (auth) |

### Categories

| Method | Endpoint              | Description     |
| ------ | --------------------- | --------------- |
| POST   | /api/categories       | List categories |
| POST   | /api/categories/show  | Show category   |
| POST   | /api/categories/store | Add category    |

### Products

| Method | Endpoint              | Description           |
| ------ | --------------------- | --------------------- |
| POST   | /api/products         | List products         |
| POST   | /api/products/show    | Show product          |
| POST   | /api/products/store   | Add product (auth)    |
| POST   | /api/products/update  | Update product (auth) |
| POST   | /api/products/destroy | Delete product (auth) |

### Orders (protected)

| Method | Endpoint                        | Description            |
| ------ | ------------------------------- | ---------------------- |
| POST   | /api/orders/index               | List all orders        |
| POST   | /api/orders/store               | Create new order       |
| POST   | /api/orders/cancel              | Cancel order           |
| POST   | /api/orders/show                | Show order details     |
| POST   | /api/orders/update_order_status | Update order status    |
| POST   | /api/orders/pick                | Assign order to driver |
| POST   | /api/orders/showAcceptedOrders  | Show accepted orders   |
| POST   | /api/orders/history             | Order history          |

---

## 🛠 Notes

* All protected endpoints require a **Sanctum auth token**
* Configure DB connection in `.env` before running migrations
* Do **not** commit `.env` or `vendor` folder to GitHub

---

## 📁 License

This project is licensed under the **MIT License**.

````

