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
- Set Database configuration parameters in .env file
- Set HRMS_URL and SPMS_URL environment variable in .env file matching the url and ports that you set while setting up the microservices.
- Run `php artisan migrate --seed`
- Run `npm run watch`
- Run `php artisan serve`
- Visit your browser at `http://127.0.0.1:8000`
- Login credentials: 
    - Email: `admin@erp.kim`
    - Password: `admin`
