
MyShoppy Ecommerce App

MyShoppy is a modern e-commerce web application built with Laravel, Livewire, and Vue.js. It provides a complete shopping experience including product listing, cart management, user authentication, admin user product management,admin Stock alert job and admin Daily sales report etc.


✨ Features

👤 User Features

* Product listing

* Cart management

* User authentication

* Secure checkout with Stripe

🛠️ Admin Features

* Product management

* Stock alert job

* Daily sales report


📦 Installation


1️⃣ Clone the Repository
```
git clone https://github.com/pramod-alpy/myshoppy.git
cd myshoppy
```
2️⃣ Install PHP Dependencies
```
composer install
```
3️⃣ Install JS Dependencies
```
npm install
```
4️⃣ Copy .env File
```
cp .env.example .env
```
5️⃣ Generate App Key
```
php artisan key:generate
```
6️⃣ Configure Database in .env
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cart
DB_USERNAME=root
DB_PASSWORD=
```
8️⃣ Run Migrations

```
php artisan migrate
```
9️⃣ Run Seeders
```
php artisan db:seed
```
🔟 Start Development Servers

Backend
```
php artisan serve

```
Frontend
```
npm run dev

```

⚙️ Tech Stack

* Backend: Laravel, MySQL, Laravel Sanctum

* Frontend: Vue.js, Tailwind CSS

* Other: Composer, npm

📧 Email Configuration

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="MyShoppy"

```

💳 Stripe Configuration

```
STRIPE_KEY=
STRIPE_SECRET=
VITE_STRIPE_KEY=


```