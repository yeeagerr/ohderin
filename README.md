# Laravel Project Setup Guide

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## 📌 About Project

Project ini menggunakan **Laravel Framework**, sebuah PHP framework yang powerful dan mudah digunakan untuk membangun aplikasi web modern.

---

## ⚙️ Requirements

Pastikan sudah terinstall:

- PHP >= 8.1  
- Composer  
- Git  
- Database (MySQL / PostgreSQL)  
- Node.js & NPM (opsional)

Cek:
```bash
php -v
composer -v
git --version


git clone https://github.com/username/nama-repo.git
cd nama-repo

composer install

cp .env.example .env

php artisan key:generate


Cari di vscode cari nama file .env dan ganti konfigurasi mysql :
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=

ke terminal lagi :
php artisan migrate

php artisan serve
http://127.0.0.1:8000

