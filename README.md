# BE Coding Standard
- The [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) ruleset to check that
repositories are following the unified coding standard for BE projects.
- The [PHPStan](https://github.com/phpstan/phpstan) default configuration file.
- PhpStorm code style and inspections (with [Php Inspections (EA Extended)](https://plugins.jetbrains.com/plugin/7622-php-inspections-ea-extended-) plugin) configuration files.

Standards
---------
For full reference of enforcements, go through `src/BECodingStandard/ruleset.xml` where each sniff is briefly described.

@TODO list of important sniffs

### Skippable sniffs
For example to skip Function comment sniff:
```
/**
 * @phpcsSuppress BECodingStandard.Commenting.FunctionComment
 */
```

- BECodingStandard.Commenting.FunctionComment
- BECodingStandard.NamingConvention.CamelCapsFunctionName
- BECodingStandard.WhiteSpace.MethodSpacing
- Some sniffs from [SlevomatCodingStandards](https://github.com/slevomat/coding-standard)

Installation
------------
You can install the BE Coding Standard as a composer dependency to your project:

```bash
$ composer require --dev brandembassy/coding-standard
```

PHP_CodeSniffer
---------------
You can run PHP_CodeSniffer with this command:

```bash
$ ./vendor/bin/phpcs --standard=BECodingStandard /path/to/some/file/to/sniff.php
```

You might also do automatic fixes using `phpcbf`:

```bash
$ ./vendor/bin/phpcbf --standard=BECodingStandard /path/to/some/file/to/sniff.php
```

PHPStan
-------
- includes [phpstan-strict-rules](https://github.com/phpstan/phpstan-strict-rules) extension
- includes [phpstan-nette](https://github.com/phpstan/phpstan-nette) extension
- includes max level configuration by default

To use default configuration include integration-phpstan.neon in your project's PHPStan config:

``` yaml
includes:
    - vendor/brandembassy/coding-standard/integration-phpstan.neon
```

PhpStorm
--------
This project contains inspections and code style configurations for PhpStorm.
- `BE Code Style.xml`
- `BE Inspections.xml`

Importing these configurations reduces number of errors in `phpcs` check before committing.

Versioning
----------
This library follows semantic versioning, and additions to the code ruleset
are only performed in major releases.

Testing
-------
@TODO: inspiration - https://github.com/doctrine/coding-standard/tree/master/tests

If you are contributing to the BE Coding Standard and want to test your contribution, you just
need to execute PHPCS with the tests folder and ensure it matches the expected report:

```bash
$ ./vendor/bin/phpcs tests/input --report=summary --report-file=phpcs.log; diff tests/expected_report.txt phpcs.log
```
