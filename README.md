...

## Cara Menjalankan Project

1. **Clone repository**

    ```sh
    git clone https://github.com/arfan0509/InstaApp.git
    cd InstaApp
    ```

2. **Install dependency PHP**

    ```sh
    composer install
    ```

3. **Install dependency JavaScript**

    ```sh
    npm install
    ```

4. **Copy file environment dan generate key**

    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

5. **Konfigurasi database**

    - Edit file `.env` dan sesuaikan konfigurasi database sesuai kebutuhan.
    - Atau Buat database baru dengan nama `instaapp_db`
    - Edit file `.env` dan pastikan konfigurasi berikut:

    ```sh
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=instaapp_db
    DB_USERNAME=(username database anda)
    DB_PASSWORD=(password username anda)
    ```

6. **Jalankan migrasi database**

    ```sh
    php artisan migrate
    ```

7. **Jalankan server Laravel**

    ```sh
    php artisan serve
    ```

8. **Akses aplikasi**
   Buka browser dan akses [http://localhost:8000](http://localhost:8000).

---
