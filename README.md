# JSON assertions for PHPUnit

![Unit tests](https://github.com/martin-helmich/phpunit-json-assert/workflows/Unit%20tests/badge.svg)
[![Code Climate](https://codeclimate.com/github/martin-helmich/phpunit-json-assert/badges/gpa.svg)](https://codeclimate.com/github/martin-helmich/phpunit-json-assert)
[![Test Coverage](https://codeclimate.com/github/martin-helmich/phpunit-json-assert/badges/coverage.svg)](https://codeclimate.com/github/martin-helmich/phpunit-json-assert/coverage)
[![Issue Count](https://codeclimate.com/github/martin-helmich/phpunit-json-assert/badges/issue_count.svg)](https://codeclimate.com/github/martin-helmich/phpunit-json-assert)

This library adds several new assertions to [PHPUnit](https://phpunit.de/)
that allow you to easily and concisely verify complex data structures (often,
but not necessarily, JSON documents) using JSONPath expressions and JSON
schemas.

## Author and copyright

Martin Helmich <kontakt@martin-helmich.de>  
This library is [MIT-licensed](LICENSE.txt).

## Installation

    $ composer require --dev helmich/phpunit-json-assert

## Compatibility

There are several release branches of this library, each of these being compatible with different releases of PHPUnit and PHP. The following table should give an easy overview:

| "JSON assertion" version | PHPUnit 4 | PHPUnit 5 | PHPUnit 6 | PHPUnit 7 | PHPUnit 8 | PHPUnit 9 |
| ------------------------ | --------- | --------- | --------- | --------- | --------- | --------- |
| v1 (branch `v1`), **unsupported** | :white_check_mark: | :white_check_mark: | :no_entry_sign: | :no_entry_sign: | :no_entry_sign: | :no_entry_sign: |
| v2 (branch `v2`) | :no_entry_sign: | :no_entry_sign: | :white_check_mark: | :white_check_mark: | :no_entry_sign: | :no_entry_sign: |
| v3 (branch `master`) | :no_entry_sign: | :no_entry_sign: | :no_entry_sign: | :no_entry_sign: | :white_check_mark: | :white_check_mark: |

When you are using `composer require` and have already declared a dependency to `phpunit/phpunit` in your `composer.json` file, Composer should pick latest compatible version automatically.

## Usage

Simply use the trait `Helmich\JsonAssert\JsonAssertions` in your test case. This
trait offers a set of new `assert*` functions that you can use in your test
cases:

```php
<?php
use Helmich\JsonAssert\JsonAssertions;
use PHPUnit\Framework\TestCase;

class MyTestCase extends TestCase
{
  use JsonAssertions;

  public function testJsonDocumentIsValid()
  {
    $jsonDocument = [
      'id'          => 1000,
      'username'    => 'mhelmich',
      'given_name'  => 'Martin',
      'family_name' => 'Helmich',
      'age'         => 27,
      'phones' => [
        'mobile' => 111,
        'home'   => 222,
      ],
      'hobbies'     => [
        'Heavy Metal',
        'Science Fiction',
        'Open Source Software',
      ]
    ];

    $this->assertJsonValueEquals($jsonDocument, '$.username', 'mhelmich');
    $this->assertJsonValueEquals($jsonDocument, '$.phones.mobile', 111);
    $this->assertJsonValueEquals($jsonDocument, '$.hobbies.0', 'Heavy Metal');
    $this->assertJsonValueEquals($jsonDocument, '$.hobbies[*]', 'Open Source Software');
  }
}
```

Most assertions take a `$jsonPath` argument which may contain any kind of
expression supported by the [JSONPath][jsonpath] library.

Alternatively, you can use the functional interface by including the file
`src/Functions.php` into your test cases:

```php
<?php
use Helmich\JsonAssert\JsonAssertions;
use PHPUnit\Framework\TestCase;

require_once('path/to/Functions.php');

class MyTestCase extends TestCase
{
  use JsonAssertions;

  public function testJsonDocumentIsValid()
  {
    $jsonDocument = [
      'id'          => 1000,
      'username'    => 'mhelmich',
      'given_name'  => 'Martin',
      'family_name' => 'Helmich',
      'age'         => 27,
      'hobbies'     => [
        "Heavy Metal",
        "Science Fiction",
        "Open Source Software"
      ]
    ];

    assertThat($jsonDocument, containsJsonValue('$.username', 'mhelmich'));
    assertThat($jsonDocument, matchesJsonConstraints([
        '$.given_name' => 'Martin',
        '$.age'        => greaterThanOrEqual(18),
        '$.hobbies'    => callback(function($a) { return count($a) > 2; })
    ]));
  }
}
```

## Assertion reference

##### `assertJsonValueEquals($doc, $jsonPath, $expected)`

Asserts that the JSON value found in `$doc` at JSON path `$jsonPath` is equal
to `$expected`.

##### `assertJsonValueMatches($doc, $jsonPath, PHPUnit_Framework_Constraint $constraint)`

Asserts that the JSON value found in `$doc` at JSON path `$jsonPath` matches
the constraint `$constraint`.

Example:

```php
$this->assertJsonValueMatches(
  $jsonDocument,
  '$.age',
  PHPUnit_Framework_Assert::greaterThanOrEqual(18)
);
```

##### `assertJsonDocumentMatches($doc, $constraints)`

Asserts that a variable number of JSON values match a constraint. `$constraints`
is a key-value array in which JSON path expressions are used as keys to a
constraint value.

Example:

```php
$this->assertJsonDocumentMatches($jsonDocument, [
    '$.username' => 'mhelmich',
    '$.age'      => PHPUnit_Framework_Assert::greaterThanOrEqual(18)
]);
```

##### `assertJsonDocumentMatchesSchema($doc, $schema)`

Assert that a given JSON document matches a certain [JSON schema][jsonschema].

Example:

```php
$this->assertJsonDocumentMatchesSchema($jsonDocument, [
    'type'       => 'object',
    'required'   => ['username', 'age'],
    'properties' => [
        'username' => ['type' => 'string', 'minLength' => 3],
        'age'      => ['type' => 'number']
    ]
]);
```

[jsonpath]: https://packagist.org/packages/softcreatr/jsonpath
[jsonschema]: http://json-schema.org/
