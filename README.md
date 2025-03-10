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

## API Documentation
### Authentication
* **Register**
Endpoint: POST /api/register
Description: Register a new user.
Request Body:
```
{
  "name": "string",
  "email": "string (unique)",
  "password": "string (min 6 characters)",
  "password_confirmation": "string",
  "roleId": "integer (optional)"
}
```
Response:
```
{
  "message": "User registered successfully",
  "data": {
    "id": "integer",
    "name": "string",
    "email": "string",
    "roleId": "integer"
  }
}
```
* **Login**
Endpoint: POST /api/login
Description: Authenticate user and get a token.
Request Body:
```
{
  "email": "string",
  "password": "string"
}
```
Response:
```
{
  "message": "Login successful",
  "access_token": "string",
  "token_type": "bearer",
  "user": {
    "id": "integer",
    "name": "string",
    "email": "string"
  }
}
```
* **Check Email** 
Endpoint: GET /api/check-email?email={email}
Description: Check if an email is already registered.
Response:
```
{
  "message": "Email check successful",
  "email_exists": "boolean"
}
```
* **Get User Info (Authenticated)**
Endpoint: GET /api/user
Headers:
```
Authorization: Bearer {token}
```
Response:
```
{
  "message": "User details retrieved",
  "total": "integer",
  "data": [
    {
      "id": "integer",
      "name": "string",
      "email": "string",
      "role": "string"
    }
  ]
}
```
* **Get User Info (Authenticated)**
List Shipments
Endpoint: GET /api/order-shipment
Query Parameters (Optional):
* sort_by: Field to sort by (default: id)
* order: Sorting order (asc or desc)
```
Authorization: Bearer {token}
```
Response:
```
{
  "message": "Order shipment details retrieved",
  "total": "integer",
  "data": [
    {
      "id": "integer",
      "namaPengirim": "string",
      "alamatPengirim": "string",
      "nomorTelponPengirim": "string",
      "namaPenerima": "string",
      "alamatPenerima": "string",
      "nomorTelponPenerima": "string",
      "descBarang": "string",
      "beratBarang": "integer",
      "hargaBarang": "integer",
      "createdBy": "string",
      "created_at": "datetime",
      "updated_at": "datetime"
    }
  ]
}
```
* **Create Shipment**
Endpoint: POST /api/order-shipment
Request
```
{
  "namaPengirim": "string",
  "alamatPengirim": "string",
  "nomorTelponPengirim": "string",
  "namaPenerima": "string",
  "alamatPenerima": "string",
  "nomorTelponPenerima": "string",
  "descBarang": "string",
  "beratBarang": "integer",
  "hargaBarang": "integer",
  "createdBy": "string"
}
```
Response:
```
{
  "message": "Order shipment details retrieved",
  "data": [
    {
      "id": "integer",
      "namaPengirim": "string",
      "alamatPengirim": "string",
      "nomorTelponPengirim": "string",
      "namaPenerima": "string",
      "alamatPenerima": "string",
      "nomorTelponPenerima": "string",
      "descBarang": "string",
      "beratBarang": "integer",
      "hargaBarang": "integer",
      "createdBy": "string",
      "created_at": "datetime",
      "updated_at": "datetime"
    }
  ]
}
```
* **Update Shipment** ga jadi dipakai
Endpoint: PUT /api/order-shipment/{id}
Request
```
{
  "namaPengirim": "string (optional)",
  "alamatPengirim": "string (optional)",
  "nomorTelponPengirim": "string (optional)",
  "namaPenerima": "string (optional)",
  "alamatPenerima": "string (optional)",
  "nomorTelponPenerima": "string (optional)",
  "descBarang": "string (optional)",
  "beratBarang": "integer (optional)",
  "hargaBarang": "integer (optional)"
}
```
Response:
```
{
  "message": "Shipment updated successfully",
  "data": {
    "id": "integer"
  }
}
```
* **Delete Shipment**
Endpoint: DELETE /api/order-shipment/{id}
Response:
```
{
  "message": "Shipment deleted successfully"
}
```
