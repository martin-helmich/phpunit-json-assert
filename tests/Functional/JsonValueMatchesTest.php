<?php
namespace Helmich\JsonAssert\Tests\Functional;

use Helmich\JsonAssert\JsonAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;

class JsonValueMatchesTest extends TestCase
{

    use JsonAssertions;

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

    public static function dataForJsonValueEquals()
    {
        $json = static::$exampleDocument;

        return [
            array($json, '$.identifier', '1234'),
            array(json_encode($json), '$.identifier', '1234'),
            array($json, '$.identifier', 1234),
            array($json, '$..identifier', '4321'),
            array($json, '$.owner.name', 'Max Mustermann'),
            array($json, '$.products.*.name', 'Roggenbrot'),
            array($json, '$.products[*].name', 'Roggenbrot'),
            array($json, '$.products[0].name', 'Roggenbrot'),
            array($json, '$.products.*.name', 'Graubrot'),
            array($json, '$.owner', [
                'identifier' => '4321',
                'name' => 'Max Mustermann',
            ]),
            array(json_encode($json), '$.owner', [
                'identifier' => '4321',
                'name' => 'Max Mustermann',
            ]),
        ];
    }

    public static function dataForJsonValueEqualsCanFail()
    {
        $json = static::$exampleDocument;

        return [
            array($json, '$.identifier', '12345'),
            array($json, '$.foobar', '12345'),
            array(json_encode($json), '$.identifier', '4231'),
            array($json, '$.identifier', false),
            array($json, '$.identifier', null),
            array($json, '$..identifier', '54312'),
            array($json, '$.owner.name', 'Horst Mustermann'),
            array($json, '$.products.*.name', 'Weißbrot'),
            array($json, '$.products[0].name', 'Graubrot'),
            array($json, '$.owner', []),
            array(json_encode($json), '$.owner', []),
        ];
    }

    /**
     * @param $jsonDocument
     * @param $jsonPath
     * @param $expectedValue
     * @dataProvider dataForJsonValueEquals
     */
    public function testJsonValueEqualsCanSucceed($jsonDocument, $jsonPath, $expectedValue)
    {
        $this->assertJsonValueEquals($jsonDocument, $jsonPath, $expectedValue);
    }

    /**
     * @param $jsonDocument
     * @param $jsonPath
     * @param $expectedValue
     * @dataProvider dataForJsonValueEqualsCanFail
     */
    public function testJsonValueEqualsCanFail($jsonDocument, $jsonPath, $expectedValue)
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertJsonValueEquals($jsonDocument, $jsonPath, $expectedValue);
    }

    /**
     * @param $jsonDocument
     * @param $jsonPath
     * @param $expectedValue
     * @dataProvider dataForJsonValueEquals
     */
    public function testJsonValueMatchesCanSucceed($jsonDocument, $jsonPath, $expectedValue)
    {
        $this->assertJsonValueMatches(
            $jsonDocument,
            $jsonPath,
            new IsEqual($expectedValue)
        );
    }

    public function testJsonValueMatchesSucceedsWithAnyConstraint()
    {
        $this->assertJsonValueMatches(
            static::$exampleDocument,
            '$.products',
            new IsType('array')
        );
    }

    /**
     * @param $jsonDocument
     * @param $jsonPath
     * @param $expectedValue
     * @dataProvider dataForJsonValueEqualsCanFail
     */
    public function testJsonValueMatchesCanFail($jsonDocument, $jsonPath, $expectedValue)
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertJsonValueMatches(
            $jsonDocument,
            $jsonPath,
            new IsEqual($expectedValue)
        );
    }

    public function testAssertThatAllValuesMatch()
    {
        $this->assertAllJsonValuesEqual(self::$exampleDocument, '$.products[*].category', 'Brot');
    }

    public function testAssertManyCanSucceed()
    {
        $this->assertJsonDocumentMatches(
            static::$exampleDocument,
            [
                '$.identifier' => 1234,
                '$.owner.name' => 'Max Mustermann',
                '$.products' => new Count(2)
            ]
        );
    }

    public function testAssertManyCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertJsonDocumentMatches(
            static::$exampleDocument,
            [
                '$.identifier' => 1234,
                '$.owner.name' => 'Max Mustermann',
                '$.products[*].name' => 'Weißbrot',
            ]
        );
    }
}
