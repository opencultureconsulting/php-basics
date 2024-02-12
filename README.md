# PHP Basics

***A collection of generic classes and useful traits for PHP projects.***

The package currently contains classes for [type-sensitive data structures](src/DataStructures/), [error and exception handlers](src/ErrorHandlers/), multiple [traits implementing standard interfaces](src/Interfaces/), and more generic [traits for common use cases](src/Traits/). They share the same design principles like property and method naming schema, highest coding standards of [PHPStan](https://phpstan.org/) and [Psalm](https://psalm.dev/), and full [PSR-12](https://www.php-fig.org/psr/psr-12/) compliance to make sure they can be combined and easily re-used in other projects.

## Quick Start

The intended and recommended way of re-using this package is via [Composer](https://getcomposer.org/). The following command will get you the latest version:

    composer require opencultureconsulting/basics

All available versions as well as further information about requirements and dependencies can be found on [Packagist](https://packagist.org/packages/opencultureconsulting/basics).

## Full Documentation

The full documentation is available on [GitHub Pages](https://opencultureconsulting.github.io/php-basics/) or alternatively in [doc/](doc/).

## Quality Gates

[![PHPCS](https://github.com/opencultureconsulting/php-basics/actions/workflows/phpcs.yml/badge.svg)](https://github.com/opencultureconsulting/php-basics/actions/workflows/phpcs.yml)
[![PHPMD](https://github.com/opencultureconsulting/php-basics/actions/workflows/phpmd.yml/badge.svg)](https://github.com/opencultureconsulting/php-basics/actions/workflows/phpmd.yml)

[![PHPStan](https://github.com/opencultureconsulting/php-basics/actions/workflows/phpstan.yml/badge.svg)](https://github.com/opencultureconsulting/php-basics/actions/workflows/phpstan.yml)
[![Psalm](https://github.com/opencultureconsulting/php-basics/actions/workflows/psalm.yml/badge.svg)](https://github.com/opencultureconsulting/php-basics/actions/workflows/psalm.yml)
