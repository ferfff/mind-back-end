<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Mind project Laravel Docker

Steps to run this app

- Download app from github [Mind back end](https://github.com/ferfff/mind-back-end).
- Run `composer install` inside project folder.
- Run `./vendor/bin/sail up` and you will se your app in [localhost](http://localhost).
- In another console check docker images with `docker ps`
- To run commands inside application run `exec -it {name of yout image} /bin/bash` to get into a internal console
- Run database migrations with `php artisan make:migration` (Super user is created by default in `database/seeders/UsersTableSeeder.php`).
- Swagger documentation API [Swagger](http://localhost/api/documentation).
- To check php unit coverage go to file://yourprojectfolder/reports/index.html.
- You can access to DB in [PHPMyAdmin](http://localhost:8001/) user:sail pass:password.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
