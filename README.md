<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Mind api project Laravel 9 and Docker

Steps to run this app

- This app works with docker, you need docker installed
- Download app from github [Mind back end](https://github.com/ferfff/mind-back-end).
- Go inside your project folder
- Run `./vendor/bin/sail up` and you will se your app in [localhost](http://localhost).
- In another console check docker images with `docker ps`
- To run commands inside application run `exec -it {name of your image} /bin/bash` to get into a internal console
- Run `composer install`.
- Run `npm install`.
- Run database migrations with `php artisan make:migration` (Super user is created by default in `database/seeders/UsersTableSeeder.php`).
- Run `npm run dev`.

Pages to use the app

- Swagger documentation API [Swagger](http://localhost/api/documentation).
- To check php unit coverage go to file://yourprojectfolder/reports/index.html.
- You can access to DB in [PHPMyAdmin](http://localhost:8001/) user:sail pass:password.

List of RESTful urls (complete documentation in Swagger)

Auth
- POST `/api/login` login
- POST `/api/register` create new user (only superadmin)
- POST `/api/logout` logout

Users
- GET `/api/users/index` Show active users
- POST `/api/users/show` Show specific user
- PUT `/api/users/update` Update user information
- DELETE `/api/users/delete` Logical deletion of user
- GET `/api/users/getinfo` Get logged user information

Accounts
- GET `/api/accounts/index` Show active accounts
- POST `/api/accounts/create` Create new account
- GET `/api/accounts/show` Show specific account
- PUT `/api/accounts/update` Update account information
- DELETE `/api/accounts/index` Logical deletion of account
- PUT `/api/accounts/add_users` Add users to account
- PUT `/api/accounts/remove_users` Remove users from account
- POST `/api/accounts/filter` Show logs of users in accounts by specific search

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
