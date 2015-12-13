<?php
namespace Helmich\JsonAssert\Tests\Functional;


use Helmich\JsonAssert\JsonAssertions;

class ConstraintTest extends \PHPUnit_Framework_TestCase
{



    use JsonAssertions;


    private static $exampleDocument = [
        'identifier' => '1234',
        'owner'      => [
            'identifier' => '4321',
            'name'       => 'Max Mustermann',
        ],
        'products'   => [
            [
                'identifier' => 500,
                'name'       => 'Roggenbrot',
                'category'   => 'Brot',
            ],
            [
                'identifier' => 501,
                'name'       => 'Graubrot',
                'category'   => 'Brot',
            ],
        ],
    ];



    public function dataForJsonValueEquals()
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
        ];
    }



    public function dataForJsonValueEqualsCanFail()
    {
        $json = static::$exampleDocument;

        return [
            array($json, '$.identifier', '12345'),
            array($json, '$.foobar', '12345'),
            array(json_encode($json), '$.identifier', '4231'),
            array($json, '$.identifier', FALSE),
            array($json, '$.identifier', NULL),
            array($json, '$..identifier', '54312'),
            array($json, '$.owner.name', 'Horst Mustermann'),
            array($json, '$.products.*.name', 'Weißbrot'),
            array($json, '$.products[0].name', 'Graubrot'),
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
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testJsonValueEqualsCanFail($jsonDocument, $jsonPath, $expectedValue)
    {
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
            new \PHPUnit_Framework_Constraint_IsEqual($expectedValue)
        );
    }



    public function testJsonValueMatchesSucceedsWithAnyConstraint()
    {
        $this->assertJsonValueMatches(
            static::$exampleDocument,
            '$.products',
            new \PHPUnit_Framework_Constraint_IsType('array')
        );
    }



    /**
     * @param $jsonDocument
     * @param $jsonPath
     * @param $expectedValue
     * @dataProvider dataForJsonValueEqualsCanFail
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testJsonValueMatchesCanFail($jsonDocument, $jsonPath, $expectedValue)
    {
        $this->assertJsonValueMatches(
            $jsonDocument,
            $jsonPath,
            new \PHPUnit_Framework_Constraint_IsEqual($expectedValue)
        );
    }



    public function testAssertThatAllValuesMatch()
    {
        $this->assertAllJsonValuesEqual(self::$exampleDocument, '$.products[*].category', 'Brot');
    }



    public function testAssertManyCanSucceed()
    {
        $this->assertJsonDocumentMatches(static::$exampleDocument, [
            '$.identifier' => 1234,
            '$.owner.name' => 'Max Mustermann',
            '$.products' => new \PHPUnit_Framework_Constraint_Count(2)
        ]);
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testAssertManyCanFail()
    {
        $this->assertJsonDocumentMatches(static::$exampleDocument, [
            '$.identifier'       => 1234,
            '$.owner.name'       => 'Max Mustermann',
            '$.products[*].name' => 'Weißbrot',
        ]);
    }

}