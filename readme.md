# Laravel PHP Framework

[![Build Status](https://travis-ci.org/okaufmann/laravel-web-money-manager-ex.svg?branch=master)](https://travis-ci.org/okaufmann/laravel-web-money-manager-ex)
[![StyleCI](https://styleci.io/repos/70249420/shield?branch=master)](https://styleci.io/repos/70249420)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/okaufmann/laravel-web-money-manager-ex/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/okaufmann/laravel-web-money-manager-ex/?branch=master)
[![Total Downloads](https://poser.pugx.org/okaufmann/laravel-web-money-manager-ex/d/total.svg)](https://packagist.org/okaufmann/laravel-web-money-manager-ex)
[![Latest Stable Version](https://poser.pugx.org/okaufmann/laravel-web-money-manager-ex/v/stable.svg)](https://packagist.org/packages/okaufmann/laravel-web-money-manager-ex)
[![Latest Unstable Version](https://poser.pugx.org/okaufmann/laravel-web-money-manager-ex/v/unstable.svg)](https://packagist.org/packages/okaufmann/laravel-web-money-manager-ex)
[![License](https://poser.pugx.org/okaufmann/laravel-web-money-manager-ex/license.svg)](https://packagist.org/packages/okaufmann/laravel-web-money-manager-ex)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Configuration

Since Money Manager EX Client search the API at /services.php some nginx config is  needed. Add the following to your site configuration:

```
location = /services.php {
        try_files $uri $uri/ /index.php?$query_string;
    }

```




## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
