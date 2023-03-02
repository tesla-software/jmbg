# JMBG Validator/Generator

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
![Build Status][ico-actions]

Validate, generate and extract data from JMBG (Unique Master Citizen Number).

## Install

Via Composer

``` bash
composer require tesla-software/jmbg
```

## Usage

### Validation
``` php
use Tesla\JMBG\JMBG;

// Check if JMBG is valid
JMBG::for('2509992391801')->isValid(); // Returns: true

// Extract birthday
JMBG::for('2509992391801')->getBirthday(); // Returns: DateTime (1992-09-25)

// Extract gedner (m for males, f for females)
JMBG::for('2509992391801')->getGender(); // Returns: m
```

### Generation
``` php
use Tesla\JMBG\Generator;

$gen = new Generator;

// Returns valid random JMBG
$gen->fake();

// Override params [day, month, year, region, gender]
$gen->fake(25, 9, 992, '57', '321');
```

## Testing

``` bash
$ composer test
```

[ico-version]: https://img.shields.io/packagist/v/tesla-software/jmbg
[ico-license]: https://img.shields.io/github/license/tesla-software/jmbg
[ico-actions]: https://img.shields.io/github/actions/workflow/status/tesla-software/jmbg/php.yml

[link-packagist]: https://packagist.org/packages/tesla-software/jmbg
