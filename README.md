[![Build Status](https://travis-ci.org/nark3d/LaravelZendSearch.svg?branch=master)](https://travis-ci.org/nark3d/LaravelZendSearch)
[![Build Status](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/badges/build.png?b=master)](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nark3d/LaravelZendSearch/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/best-served-cold/laravel-zendsearch.svg)](https://packagist.org/packages/best-served-cold/laravel-zendsearch)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d042f6a1-0877-441c-92b7-bb5fe51d6466/mini.png)](https://insight.sensiolabs.com/projects/d042f6a1-0877-441c-92b7-bb5fe51d6466)

# LaravelZendSearch
A fast implementation of [ZendSearch](http://zf2.readthedocs.io/en/latest/tutorials/lucene.intro.html) hooking into Laravel eloquent.  Utilise the power of Lucene without installing a secondary service such as Elasticsearch or Solr. 

After using a couple of packages for [ZendSearch](http://zf2.readthedocs.io/en/latest/tutorials/lucene.intro.html) in Laravel I was disappointed with the performance, so I created my own. 
## Installation

Follow these steps to get the package in place:

### Setup

```shell
composer require best-served-cold/laravel-zendsearch
```

Update composer and then add the `ServiceProvider` to `config/app.php`:

```php
'providers' => [
  // ...
	BestServedCold\LaravelZendSearch\Laravel\ServiceProvider::class,
],
```

I'm not keen on Facades, but if you want to use one, add it to the aliases in `config/app.php`:

```php
'aliases' => [
   // ...
	'Search' => BestServedCold\LaravelZendSearch\Laravel\Facade::class,
],
```

### Publish

Get your config published:

```bash
php artisan vendor:publish
```

## Usage

### Indexing

Add the ```SearchTrait``` and use it in the models you want to use:

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
// ...
    public static function searchFields()
    {
        self::setSearchFields(['some', 'fields']);
    }
// ...
```

If you want to "boost" fields, then add the following method:

```php
// ...
    public static function boostFields()
    {
        self::setBoostFields(['some' => 0.8, 'fields' => 1.0]);
    }
// ...
```

The index will build automatically from there.

#### Relations

If you want to index relations, make sure you create your ```getRelationAttribute()``` method and then add the relation to the ```protected $appends = [$relation];``` array in your model.

### Building

If you have existing data or have changed your search fields, you can rebuild the index from scratch:

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

I'll work on a scheduler in the near future, but make sure you optimise your index regularly.  I'd suggest scheduling it yourself for now every hour or so.

## Searching

### Basic

Create a search instance:
```php
$search = UserModel::search();

// add your query
$search->where('string', 'field');

// add an exact match
$search->match('string', 'field');

// Search all fields
$search->where('string');

// limit your query
$search->limit(10);

// get your hits (primary keys in an array)
$ids = $search->hits();

// and or, get your eloquent collection
$result = $search->get();

// Or chain it:
$result = User::search()->where('term', 'field')->limit(15)->offset(10)->get();
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

// Get the last query
BestServedCold\LaravelZendSearch\Lucene\Search::getLastQuery()->__toString();
```

## Filters

There are two basic filters implemented in the configuration which you can override, StopWords and ShortWords.  You can, however, change both the filters and the analyzer manually to preset ZendSearch classes or custom classes:

```php
BestServedCold\LaravelZendSearch\Lucene\Filter::setAnalyzer(
    new ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive;
);

BestServedCold\LaravelZendSearch\Lucene\Filter::addFilter(
    new ZendSearch\Lucene\Analysis\TokenFilter\LowerCaseUtf8;
);
```

## Helpers

To assist in debugging, there are a few helpers which can assist:

```php
// Returns the last ZendSearch\Lucene\Document inserted.
var_dump(BestServedCold\LaravelZendSearch\Lucene\Store::getLastInsert());

// Returns the current set of filters or analyzer
var_dump(BestServedCold\LaravelZendSearch\Lucene::getFilters());
var_dump(BestServedCold\LaravelZendSearch\Lucene::getAnalyzer());

// Returns the last ZendSearch\Lucene\Search\Query\Boolean object to interrogate.
var_dump(BestServedCold\LaravelZendSearch\Lucene::getLastQuery()->__toString());
```

## Features to come

This is the first stable version which I've made mainly for my own use on another project.  I will add the following in due course though.

* Scheduled optimisation out of the box
* Option passthrough added for Wildcard, Phrase, Fuzzy
* Add in highlighting options
