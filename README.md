<p align="center">
    <h1 align="center">Banking API</h1>
    <h3 align="center">An internal API for a fake financial institution using PHP and Laravel</h3>
<p align="center">
    API Documentation with Postman
    <br />
    <a href="https://documenter.getpostman.com/view/11198876/2s8YmHwQDM"><strong>Explore API docs »</strong></a>
    <br />
    <br />
    <a href="https://banking-api-platform.herokuapp.com/api/">https://banking-api-platform.herokuapp.com/api/</a>
</p>

## Table of Contents
1. [Brief](#brief)
2. [Features](#features)
3. [Requirements](#requirements)
4. [Installation](#installation)
5. [Usage](#usage)
6. [Testing](#testing)

## Brief
While modern banks have evolved to serve plethora of functions, at their core, banks must provide certain basic
features. The Banking API platform is to build the basic REST API for one of those banks which can be consumed by
multiple frontends(web, IOS, Android, etc).

## Features
- [x] There should be API routes that allow them to:
    - Authenticate users
    - Create a new bank account for a customer, with an initial deposit amount. A single customer may have multiple bank accounts.
    - Transfer amounts between any two accounts, including those owned by different customers.
    - Retrieve balances for a given account.
    - Retrieve transfer history for a given account.
- [x] All endpoints should only be accessible if an API key is passed as a header.
- [x] All role-based endpoints should require authentication.
- [x] Write tests for your business logic.
- [x] Provide a documentation (published with Postman) that says what endpoints are available and the kind of parameters they expect.
- [x] You are expected to design all required models and routes for your API.

## Requirements
Make sure your server meets the following requirements.

-   Mysql server 8.0.+
-   Composer installed 2.2.+
-   PHP Version 8.1.+

## Installation
Install composer with the help of the instructions given [here](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
``` bash  
$ wget https://getcomposer.org/composer.phar
$ chmod +x composer.phar
$ mv composer.phar /usr/local/bin/composer
```

Fork and/or clone this project by running the following command
``` bash  
 git clone  https://github.com/umagloire99/banking-api
```

Navigate into the project's directory
``` bash  
 cd banking-api 
```

Copy .env.example for .env and modify according to your credentials
```bash
 cp .env.example .env
```

Run this command will install all dependencies needed by the banking API to run successfully!
```bash
 composer install --prefer-dist
```

Generate application key
```bash
 php artisan key:generate
```

This command will help migrate the database and populate the database!
```bash
 php artisan migrate --seed
```

Generate Oauth2 access token
```bash
 php artisan passport:install
```

Generate API Key that will be passed as a header in all your request
```bash
 php artisan apikey:generate
```

## Usage
Run the default laravel server for local deployment
```bash
php artisan serve
```
- Online API Documentation: [https://documenter.getpostman.com/view/11198876/2s8YmHwQDM](https://documenter.getpostman.com/view/11198876/2s8YmHwQDM)
- Hosting: [https://banking-api-platform.herokuapp.com/api/]("https://banking-api-platform.herokuapp.com/api/")


## Testing
Run this command to test the different endpoints
```bash
composer test
```
