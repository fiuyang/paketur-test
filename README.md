## Requirements
- PHP 8.2
- [Composer](https://composer.org)
- PostgresSQL 11

## Installation
1.  Clone the repository
    ```
    git clone https://github.com/fiuyang/paketur-test.git
    cd paketur-test
    ```
2.  Copy environment
    ```
    cp .env.example .env
    ```
3. Setup your configuration database
    ```
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=db_paketur
    DB_USERNAME=postgres
    DB_PASSWORD=
    ```
4.  Install dependencies
    ```
    composer install
    ```
5.  Generate key
    ```
    php artisan key:generate
    php artisan jwt:secret
    ```
6.  Run migration and seeder
    ```
    php artisan migrate --seed
    ```
7.  Run the program
    ```
    php artisan serve
    ```
8   Run unit test
    ```
    php artisan test
    ```

## API Documentation
API documentation on [here](https://github.com/fiuyang/paketur-test/tree/main/docs)
