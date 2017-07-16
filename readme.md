# Laravel Money Manager Ex WebApp

[![Build Status](https://travis-ci.org/okaufmann/laravel-web-money-manager-ex.svg?branch=master)](https://travis-ci.org/okaufmann/laravel-web-money-manager-ex)
[![StyleCI](https://styleci.io/repos/70249420/shield?branch=master)](https://styleci.io/repos/70249420)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/okaufmann/laravel-web-money-manager-ex/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/okaufmann/laravel-web-money-manager-ex/?branch=master)
[![Total Downloads](https://poser.pugx.org/okaufmann/laravel-web-money-manager-ex/d/total.svg)](https://packagist.org/okaufmann/laravel-web-money-manager-ex)
[![Latest Stable Version](https://poser.pugx.org/okaufmann/laravel-web-money-manager-ex/v/stable.svg)](https://packagist.org/packages/okaufmann/laravel-web-money-manager-ex)
[![Latest Unstable Version](https://poser.pugx.org/okaufmann/laravel-web-money-manager-ex/v/unstable.svg)](https://packagist.org/packages/okaufmann/laravel-web-money-manager-ex)
[![License](https://poser.pugx.org/okaufmann/laravel-web-money-manager-ex/license.svg)](https://packagist.org/packages/okaufmann/laravel-web-money-manager-ex)

Laravel Money Manager Ex - WebApp allow you to insert new transaction directly from every device: It only needs a browser with HTML5 and internet connection to your webserver.
Its claim is to add only a "transaction remember" with only some essential data, that will be reviewed calmly in desktop version.
All transaction will be in fact downloaded at first startup of MoneyManagerEx desktop version, opening and reviewing transactions one by one. At the same time desktop version will update account, payees and categories in WebApp to keep them in sync between them.

## Requirements
- PHP 7.0+
- Imagick (you can ignore this by using --ignore-platform-reqs when installing with composer)
- MySql 5.7+ or sqlite

## Installation

Run the following command in the directory you wanna place the app:

```commandline
composer create-project --prefer-dist okaufmann/laravel-web-money-manager-ex .
```

**BETA**
If you want the latest beta version, use the following command:

```commandline
composer create-project --prefer-dist --stability=dev okaufmann/laravel-web-money-manager-ex .
```

## Configuration

Edit the following parameters to match your environment:
```
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db name
DB_USERNAME=username
DB_PASSWORD=password
```
**API for Monex Manager Ex Client**

Since Money Manager EX Client search the API at /services.php some nginx config is needed. Add the following to your site configuration to make it work with the Laravel Routing System:

```
location = /services.php {
        try_files $uri $uri/ /index.php?$query_string;
    }

```

## Transaction API

You can add and get transactions via API.

**Authentication**
Visit your user profile under `/user` to get the api key.

Append `?api_token={your api key}` to each request (as query parameter). Also you have to set the Headers `Accept` and `Content-Type` to `application/json`.

#### Create Transaction
 
**POST** data with the following schema to `api/v1/transactions`
```json
{
    "transaction_date": "2017-07-16T12:00:00+00:00",
    "transaction_type": "Deposit",
    "transaction_status": "Reconciled",
    "account" : "Primary Account",
    "to_account":  "Secondary Account",
    "payee": "Neo",
    "category": "Bills",
    "subcategory": "Internet",
    "amount" : 13.37,
    "notes" : "Lorem Ipsum is simply dummy text of the printing and typesetting industry."
}
```

**Properties:**

- `transaction_date`: _(Optional)_ Date of the transaction in Iso 8601 format.
- `transaction_type`: Type of the transaction (`Withdrawal`, `Deposit` , `Transfer`).
- `transaction_status`: _(Optional)_ Status of the transaction (`Reconciled`, `Void`, `Follow Up`, `Duplicate`) 
- `to_account`: _(Optional*)_ Account where to transfer the amount. **Only considered if `transaction_type` is set to `Transfer`
- `payee`: Person who who gets or give the money (If the person was not found in your payees, it will be created automatically).
- `category`: Category of your transaction. See your Categories in the Money Manager EX Client. 
- `subcategory`: _(Optional)_ Same as Categories.
- `account`: Amount of the Transaction (e.g. `15`, `19.19`)
- `notes`: (Optional) Additional notes for your Transaction. 

#### Get Transactions

You can receive all created transactions under `api/v1/transactions`.

**Parameters**

- `sort`: _(Optional)_ Sort by a column (e.g. `sort=created_at|desc`).
- `page`: _(Optional)_ Page to fetch data from (e.g. `page=2`).

#### Edit Transaction
TODO

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

Laravel Money Manager Ex - WebApp is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
