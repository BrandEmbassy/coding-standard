# BE Integration Coding Standard

The [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) ruleset to check that
repositories are following the unified coding standard for BE integrations

Standards
---------

For full reference of enforcements, go through `src/BE/CodingStandard/ruleset.xml` where each sniff is briefly described.

Installation
------------

You have two possibilities to use the BE Integration Coding Standard with PHP_CodeSniffer in a particular project.

### 1. As a composer dependency of your project

You can install the BE Integration Coding Standard as a composer dependency to your project:

```bash
$ php composer require --dev brandembassy/integration-coding-standard
```

Then you can use it like:

```bash
$ ./vendor/bin/phpcs --standard=vendor/brandembassy/integration-coding-standard /path/to/some/file/to/sniff.php
```

You might also do automatic fixes using `phpcbf`:

```bash
$ ./vendor/bin/phpcbf --standard=vendor/brandembassy/integration-coding-standard /path/to/some/file/to/sniff.php
```

Versioning
----------

This library follows semantic versioning, and additions to the code ruleset
are only performed in major releases.

Testing
-------
@TODO: inspiration - https://github.com/doctrine/coding-standard/tree/master/tests

If you are contributing to the BE Integration Coding Standard and want to test your contribution, you just
need to execute PHPCS with the tests folder and ensure it matches the expected report:

```bash
$ ./vendor/bin/phpcs tests/input --report=summary --report-file=phpcs.log; diff tests/expected_report.txt phpcs.log
```
