# Laravel Polls

[![CircleCI](https://dl.circleci.com/status-badge/img/gh/andrewtweber/laravel-polls/tree/main.svg?style=shield&circle-token=fd7302af8a39c2fec7e686a61cce10ffb00763dd)](https://dl.circleci.com/status-badge/redirect/gh/andrewtweber/laravel-polls/tree/main)

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

``` bash
composer require andrewtweber/laravel-polls
```

To publish the vendor files (this should happen automatically):

```
php artisan vendor:publish --provider="Andrewtweber\Providers\PollsServiceProvider"
```

This will publish the files `config/polls.php`, translations, and views.

## Laravel Nova

If you have Laravel Nova, copy the `src/Nova/*.stub` files into your Nova models directory.

This will allow you to create and update polls.

## Todo

* Additional views not using Bootstrap
* More config options (User model, enable or disable guest votes, etc.)
* Named routes
