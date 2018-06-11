# UUID version 4

[![Build Status](https://travis-ci.org/pwm/uuid-v4.svg?branch=master)](https://travis-ci.org/pwm/uuid-v4)
[![codecov](https://codecov.io/gh/pwm/uuid-v4/branch/master/graph/badge.svg)](https://codecov.io/gh/pwm/uuid-v4)
[![Maintainability](https://api.codeclimate.com/v1/badges/5a1d7cae125323260e96/maintainability)](https://codeclimate.com/github/pwm/uuid-v4/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/5a1d7cae125323260e96/test_coverage)](https://codeclimate.com/github/pwm/uuid-v4/test_coverage)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Implementation of the [RFC 4122](https://tools.ietf.org/html/rfc4122) UUID version 4 (variant 1) data type. UUID stands for Universally Unique Identifier.

## Table of Contents

* [Why](#why)
* [Requirements](#requirements)
* [Installation](#installation)
* [Usage](#usage)
* [How it works](#how-it-works)
* [Tests](#tests)
* [Changelog](#changelog)
* [Licence](#licence)

## Why

We already have comprehensive UUID libraries in PHP, eg. [ramsey/uuid](https://github.com/ramsey/uuid).

My goal was to create a minimalistic implementation of the version 4 UUID spec. UUIDv4-s are randomly generated which makes implementation trivial as it requires nothing other than a random external seed value.

The decision to only implement version 4 resulted in a tiny functional library capturing the UUIDv4 data type that can be understood in minutes. Creation happens using a pure function that takes the random seed and is trivially testable.

## Requirements

PHP 7.1+

## Installation

    $ composer require pwm/uuid-v4

## Usage

Creating a UUIDv4 data type from a valid string:

```php
$uuidV4String = '30313233-3435-4637-b839-616263646566';

$uuidV4 = new UuidV4($uuidV4String); // the data type

assert($uuidV4 instanceof UuidV4);
assert($uuidV4String === (string)$uuidV4);
```

Creating a random UUIDv4 from a 16 byte random seed:

```php
$uuidV4 = UuidV4::createFrom(random_bytes(16));

assert($uuidV4 instanceof UuidV4);
```

Creating from the same seed produces the same UUIDv4:

```php
$seed = random_bytes(16);

assert((string)UuidV4::createFrom($seed) === (string)UuidV4::createFrom($seed));
```
 
## How it works

UUID version 4 is defined in [RFC 4122](https://tools.ietf.org/html/rfc4122) as a sequence of 128 bits, where 6 bits are fixed (4 for the version, 2 for the variant) leaving 122 randomly generated bits for entropy.

`UuidV4` is a simple data type that can only be created from valid UUIDv4 strings. It comes with a `createFrom()` function that facilitates the creation of random UUIDv4-s from 16 byte random seeds. It is to be used with PHP's `random_bytes()` function.

## Tests

	$ vendor/bin/phpunit
	$ composer phpcs
	$ composer phpstan
	$ composer infection

## Changelog

[Click here](changelog.md)

## Licence

[MIT](LICENSE)
