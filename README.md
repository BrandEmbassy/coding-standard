# Brand Embassy Coding Standard
- The [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) and [cs-fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) rules to check that
repositories are following the unified coding standard for Brand Embassy projects.
- The [PHPStan](https://github.com/phpstan/phpstan) default configuration file.
- PhpStorm code style and inspections (with [Php Inspections (EA Extended)](https://plugins.jetbrains.com/plugin/7622-php-inspections-ea-extended-) plugin) configuration files.

Standards
---------
For full reference of enforcements, go through `default-ecs.php` where each sniff / fixer deviating from default set lists is briefly described.

@TODO list of important sniffs

### Skippable sniffs / fixers
Skipping of sniffs / fixers in general or for particular files should be configured in the `ecs.php` of your project. This file should leverage the `default-ecs.php` as the default configuration, but it expected that you will make adjustments to fit your project needs.

Installation
------------
You can install the Brand Embassy Coding Standard as a composer dependency to your project:

```bash
$ composer require --dev brandembassy/coding-standard
```

ECS
---------------
You can run ecs with this command (without performing automatic fixes):

```bash
$ ./vendor/bin/ecs check --ansi
```

Or with automatic fixes:

```bash
$ ./vendor/bin/ecs check --ansi --fix
```

You can use the `--clear-cache` option to clear the cache before applying the fixers and sniffers:

```bash
$ ./vendor/bin/ecs check --ansi --fix --clear-cache
```

PHPStan
-------
- includes [phpstan-strict-rules](https://github.com/phpstan/phpstan-strict-rules) extension
- includes [phpstan-nette](https://github.com/phpstan/phpstan-nette) extension
- includes max level configuration by default

To use default configuration include default-phpstan.neon in your project's PHPStan config:

``` yaml
includes:
    - vendor/brandembassy/coding-standard/integration-phpstan.neon
```

PhpStorm
--------
This project contains inspections and code style configurations for PhpStorm.
- `BrandEmbassyCodeStyle.xml`
- `BrandEmbassyInspections.xml`

Importing these configurations reduces number of errors in `phpcs` check before committing.

Versioning
----------
This library follows semantic versioning, and additions to the code ruleset
are only performed in major releases.

Testing
-------
@TODO: inspiration - https://github.com/doctrine/coding-standard/tree/master/tests

If you are contributing to the Brand Embassy Coding Standard and want to test your contribution, you just
need to execute PHPCS with the tests folder and ensure it matches the expected report:

```bash
$ ./vendor/bin/phpcs tests/input --report=summary --report-file=phpcs.log; diff tests/expected_report.txt phpcs.log
```
