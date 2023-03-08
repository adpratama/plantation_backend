<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Starter Aplikasi Laravel Bitcode.id

Starter aplikasi ini adalah kerangka dasar untuk memulai project, sudah terdapat contoh CRUD dan autentikasinya. Tools yang digunakan hingga menjadi project utuh adalah Backend Laravel dan Frontend NuxtJS.

## Konsep aplikasi 

Single Page Application (SPA) yang mendukung SEO (NuxtJS SSR) dan Progressive Web Application (memungkinkan aplikasi diinstal pada smartphone)
- contoh [http://v2-backend.fbk.id](http://v2.backend.fbk.id) dan [http://v2.fbk.id](http://v2.fbk.id)

## Fitur

- Autentikasi dengan Laravel Sanctum, support session dan token, sangat ideal untuk aplikasi SPA [menurut dokumentasinya](https://laravel.com/docs/8.x/sanctum)
- Multiple Role (Superadmin, admin, user, dst), standar untuk setiap project Bitcode.id
- Middleware Superadmin dan admin, standar untuk setiap project Bitcode.id
- Create dan Update dalam satu method (updateOrCreate)
- Sample CRUD Blog dan Kategori sudah support SEO dengan menyertakan field meta deksripsi dan tag
- Sample pengaturan untuk membuat pengaturan aplikasi
- Semua Route di routes/web.php, api hanya jika dibutuhkan untuk pengembanga mobile
- Route digroup berdasarkan Controller untuk mudah dikenali
- Support field provinsi, kota, kecamatan untuk tiap user
- File manager dengan Unisharp Laravel Filemanager

## Eloquent dan query builder

Disaat saya butuh performa lebih cepat saya menggunakan query builder, contoh di LandingController sebagai konten halaman utama agar user segera mendapatkan kontennya.

## Instalasi

- Clone atau pull repository ini
- Buat database
- Import indonesia.sql yang ada pada repository ini ke database
- Jalankan composer install
- Buat .env bisa mengikuti .env.example.txt
- Setup isian .env terutama SESSION_DOMAIN, FRONTEND, dan pengaturan database
- Jalankan migration

## Pengujian

Idealnya development dilakukan di Linux dan saya biasa menggunakan LEMP (Linux, Nginx, MySQL, PHP), agar lingkungan local dan server sama. Saya biasa simulasikan project lokal di linux dengan domain

- Backend = backend.starter.local
- Frontend = starter.local

Sehingga pada .env.example.txt anda akan menemukan SESSION_DOMAIN = '.starter.local' artinya domain dan subdomain sarter.local diberikan hak autentikasi.

- Jika anda menggunakan domain percontohan yang lain jangan lupa menambahkannya pada allowed_origins pada config/cors.php

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
