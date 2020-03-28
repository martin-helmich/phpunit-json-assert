<?php
namespace Helmich\JsonAssert\Tests\Functional;

use PHPUnit\Framework\TestCase;

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
        $this->assertThat(self::$exampleDocument, containsJsonValue('$.identifier', 1234));
    }

    public function testAssertThatJsonDocumentMatchesJsonConstraints()
    {
        $this->assertThat(
            self::$exampleDocument,
            matchesJsonConstraints(
                [
                    '$.owner.name' => $this->equalTo('Max Mustermann'),
                    '$.products[*].identifier' => $this->greaterThanOrEqual(500),
                    '$.products[*].name' => $this->logicalNot($this->equalTo('Weißbrot'))
                ]
            )
        );
    }
}
