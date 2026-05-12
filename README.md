## String Sequence


[![PHP Composer](https://github.com/pwsdotru/string-sequence/actions/workflows/php.yml/badge.svg)](https://github.com/pwsdotru/string-sequence/actions/workflows/php.yml)
[![phpunit](https://github.com/pwsdotru/string-sequence/actions/workflows/phpunit.yml/badge.svg)](https://github.com/pwsdotru/string-sequence/actions/workflows/phpunit.yml)

[![PHP Composer 74](https://github.com/pwsdotru/string-sequence/actions/workflows/php74.yml/badge.svg)](https://github.com/pwsdotru/string-sequence/actions/workflows/php74.yml)

## Versions

* 1.x.x (branch [php74](https://github.com/pwsdotru/string-sequence/tree/php74)) for PHP 7.4 
* 2.x.x (branch [master](https://github.com/pwsdotru/string-sequence/)) for PHP 8.3

## Install

Via composer

````
composer require pwsdotru/string-sequence
````

## Usage

````php
use StringSequence\Sequencer;

$seq = new Sequencer(10);
$pages = $seq->add("2,4, 7-8")->get();
````