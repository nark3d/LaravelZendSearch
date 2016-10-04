[![Build Status](https://travis-ci.org/nark3d/LaravelZendSearch.svg?branch=master)](https://travis-ci.org/nark3d/LaravelZendSearch)
[![Build Status](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/badges/build.png?b=master)](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d042f6a1-0877-441c-92b7-bb5fe51d6466/mini.png)](https://insight.sensiolabs.com/projects/d042f6a1-0877-441c-92b7-bb5fe51d6466)
# LaravelZendSearch
A fast implementation of ZendSearch hooking into Laravel eloquent.  

After using a couple of packages for ZendSearch in Laravel I was disappointed with the performance, so I created my own.

## Installation

Follow these steps to get the package in place

### Setup

```shell
composer require best-served-cold/laravel-zendsearch
```

Update composer and then add the `ServiceProvider` to `config/app.php`

```php
'providers' => [
  // ...
	BestServedCold\LaravelZendSearch\Laravel\ServiceProvider::class,
],
```

I'm not keen on Facades, but if you want to use one, add it to the aliases in `config/app.php`

```php
'aliases' => [
   // ...
	'Search' => BestServedCold\LaravelZendSearch\Laravel\Facade::class,
],
```

### Publish

Get your config published

```bash
php artisan vendor:publish
```

## Usage

### Indexing

Simply add the ```SearchTrait``` and use it in the models you want to use.

```php

// ...

use BestServedCold\LaravelZendSearch\Laravel\SearchTrait;

class User extends Model
{
    use SearchTrait;
    
// ...
```

Then add the method ```searchFields()``` and populate it with the fields you want to be indexed:

```php
    public static function searchFields()
    {
        self::setSearchFields(['some', 'fields']);
    }
```

And away you go, your index will build automatically from there.

### Building

If you have existing data or have changed your search fields, you can simply rebuild the index from scratch:

```shell
php artisan search:rebuild --verbose
```

Destroy:
```shell
php artisan search:destroy --verbose
```

Optimise:
```shell
php artisan search:optmise --verbose
```

I'll work on a scheduler in the near future, but make sure you optimise your index regularly.  I'd suggest scheduling it yourself for now regularly.

## Searching

### Basic

Create a search instance:
```php
$search = UserModel::search();

// add your query
$search->where('field', 'name');

// limit your query
$search->limit(10);

// get your hits (primary keys in an array)
$ids = $search->hits();

// and or, get your eloquent collection
$result = $search->get();

// Or just chain it:
$result = User::search()->where('term', 'field')->limit(15)->get();
```

### Advanced

```php
$search = UserModel::search();

// Wildcard
$search->wildcard('ter*', 'field');

// Phrase
$search->phrase('this is the phrase', 'field', [1, 2, 3, 4]);

// Fuzzy
$search->fuzzy('term', 'field');

// Term
$search->term('complete_term', 'field');
```

## Features to come

This is my first iteration and I've made it mainly for my own use on another project, but I'll be adding the following in due course

* Boosting configuration within the model
* Stopwords and stemwords
* Scheduled optimisation out of the box
* Tidy up of the unit tests, they're a bit messy at the moment
* Option passthrough added for Wildcard, Phrase, Fuzzy
