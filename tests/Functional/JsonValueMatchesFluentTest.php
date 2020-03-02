<?php
namespace Helmich\JsonAssert\Tests\Functional;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;
use function PHPUnit\Framework\greaterThanOrEqual;
use function PHPUnit\Framework\logicalNot;

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
