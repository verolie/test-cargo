# Shipment Cargo #
## Instalasi ##
Sebelum menjalankan sistem lakukan instalasi terlebih dahulu, berikut ini merupakan langkah instalasi
* Siapkan database PostgreSql
* Kemudian ke folder website di lokal, jalankan composer install
* cp .env.example .env
* ubah confiq database menjadi
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=3333
DB_DATABASE=databaseName
DB_USERNAME=username
DB_PASSWORD=password
```
* Jalankan ini di command prompt untuk menggenarate key laravel
```
 php artisan key:generate
```
* Jalankan ini di command prompt untuk menggenarate key jwt
```
 php artisan jwt:secret 
```
* Jalankan fungsi migrate dan seeder
```
php artisan migrate:refresh --seed
```
* Aplikasi dapat dijalankan dengan menggunakan perintah
```
php artisan serve
```
