# PANDUAN INSTALASI

## Informasi Saya
1. PHP Version Saya: 8.3.21
2. MySQL Version Saya: 8.0.30
3. Framework: Laravel 11
4. Username pada aplikasi: admin
5. Password pada aplikasi: password

## Langkah-Langkah

### Clone Github
```bash
https://github.com/rakafebriansy/sistem-informasi-pemesanan-kendaraan-tambang.git
```

### Install Dependencies
```bash
composer update
```

### Set Up Environment
```bash
cp .env.example .env
```

### Generate App Key
```bash
php artisan key:generate
```

### Create Database Schema
```bash
php artisan migrate
```

### Seeding Database
```bash
php artisan db:seed
```

### Run App in Local
```bash
php artisan serve
```