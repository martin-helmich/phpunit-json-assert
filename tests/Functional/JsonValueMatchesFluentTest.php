<?php
namespace Helmich\JsonAssert\Tests\Functional;

use PHPUnit_Framework_TestCase as TestCase;

class JsonValueMatchesFluentTest extends TestCase
{

    private static $exampleDocument = [
        'identifier' => '1234',
        'owner' => [
            'identifier' => '4321',
            'name' => 'Max Mustermann',
        ],
        'products' => [
            [
                'identifier' => 500,
                'name' => 'Roggenbrot',
                'category' => 'Brot',
            ],
            [
                'identifier' => 501,
                'name' => 'Graubrot',
                'category' => 'Brot',
            ],
        ],
    ];

    public function testAssertThatJsonDocumentContainsJsonValue()
    {
        assertThat(self::$exampleDocument, containsJsonValue('$.identifier', 1234));
    }

    public function testAssertThatJsonDocumentMatchesJsonConstraints()
    {
        assertThat(
            self::$exampleDocument,
            matchesJsonConstraints(
                [
                    '$.owner.name' => equalTo('Max Mustermann'),
                    '$.products[*].identifier' => greaterThanOrEqual(500),
                    '$.products[*].name' => logicalNot(equalTo('Weißbrot'))
                ]
            )
        );
    }
}