# API & Dashboard

The project consists of two parts:

* API
* Admin panel

## Summary

* PHP 7.2
* Laravel 6

## Initialize project

* Go to project directory
* Run `sh init.sh`

## Generate Stripe plans for testing
* Go to project directory 
* Run `php artisan stripe:plans`

## Generate Postman collection

* Run `php artisan apidoc:generate` to generate API documentation
* Go to `/storage/app/apidoc` and get `collection.json`

Note: Rebuild API documentation using `php artisan apidoc:rebuild`.
