# Enterprise Resource Planning (ERP)

## Technologies
- [Laravel >= 7.*](https://laravel.com/)
- Microservice Architecture
    - [HRMS](https://github.com/oworyoakim/hrms.git) service
    - [SPMS](https://github.com/oworyoakim/spms.git) service
## Requirements
- [PHP >= 7.2.5](https://www.php.net/)
    - BCMath PHP Extension
    - Ctype PHP Extension
    - Fileinfo PHP extension
    - JSON PHP Extension
    - Mbstring PHP Extension
    - OpenSSL PHP Extension
    - PDO PHP Extension
    - Tokenizer PHP Extension
    - XML PHP Extension
- [Composer](https://getcomposer.org/) PHP package manager

## Instructions
- Clone this repository
- CD to project directory
- Run `composer install`
- Run `npm install`
- Run `cp .env.example .env`
- Run `php artisan key:generate`
- Run `php artisan storage:link`
- Set Database configuration parameters in .env file
- Set SPMS_APP_BASE_URL (without `http://`), SPMS_PORT, SPMS_DB_HOST, SPMS_DB_DATABASE, SPMS_DB_USERNAME, SPMS_DB_PASSWORD, and SPMS_CACHE_KEY environment variable in .env file matching the database, URL and ports that the SPMS microservice will use.
- Set HRMS_APP_BASE_URL (without `http://`), HRMS_PORT, HRMS_DB_HOST, HRMS_DB_DATABASE, HRMS_DB_USERNAME, HRMS_DB_PASSWORD, and HRMS_CACHE_KEY environment variable in .env file matching the database, URL and ports that the HRMS microservice will use.
- Run `php artisan migrate --seed`
- Run `npm run watch`
- Run `php artisan serve`
- Run `docker-compose up -d`
- Visit your browser at `http://127.0.0.1:8000`
- Login credentials: 
    - Email: `admin@erp.kim`
    - Password: `admin`
