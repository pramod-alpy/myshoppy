
MyShoppy Ecommerce App

MyShoppy is a modern e-commerce web application built with Laravel, Livewire, and Vue.js. It provides a complete shopping experience including product listing, cart management, user authentication, admin user product management,admin Stock alert job and admin Daily sales report etc.

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
🔟 Start Backend Server & Front end

```
php artisan serve

npm run dev
```


⚙️ Tech Stack

* Backend: Laravel, MySQL, Laravel Sanctum

* Frontend: Vue.js, Tailwind CSS

* Other: Composer, npm