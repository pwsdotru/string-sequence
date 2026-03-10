<?php

declare(strict_types=1);

namespace Tests;

use StringSequence\Sequencer;
use ReflectionClass;
use PHPUnit\Framework\TestCase;

class SequencerTest extends TestCase
{
    public function testContructDefault(): void
    {
        $obj = new Sequencer();
        $result = $obj->get();
        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    /**
     * @dataProvider constructSet
     * @param int $length
     * @param array $expected
     */
    public function testConstruct(int $length, array $expected): void
    {
        $obj = new Sequencer($length);
        $result = $obj->get();
        self::assertIsArray($result);
        self::assertCount($length, $result);
        self::assertEquals($result, $expected);
    }

    public static function constructSet(): array
    {
        return [
            [
                0,
                [],
            ],
            [
                2,
                [1 => false, 2 => false]
            ],
        ];
    }

    /**
     * @dataProvider getTokensSet
     * @param string $input
     * @param array $expected
     * @covers \StringSequence\Sequencer::getTokens
     */
    public function testGetTokens(string $input, array $expected): void
    {
        $obj = new Sequencer();
        $reflection = new ReflectionClass($obj);
        $method = $reflection->getMethod('getTokens');
        $method->setAccessible(true);
        $result = $method->invoke($obj, $input);
        self::assertIsArray($result);
        self::assertEquals($expected, $result);
    }

    public static function getTokensSet(): array
    {
        return [
            [
                "1,2,3",
                ["1", "2", "3"],
            ],
            [
                "",
                [],
            ],
            [
                "*/2",
                ["*/2"],
            ],
            [
                "1, 4, */2,4-5",
                ["1", "4", "*/2", "4-5"],
            ]
        ];
    }

    /**
     * @dataProvider isIntNumericSet
     * @param string $input
     * @param bool $expected
     * @covers \StringSequence\Sequencer::isIntNumeric
     */
    public function testIsIntNumeric(string $input, bool $expected)
    {
        $obj = new Sequencer();
        $reflection = new ReflectionClass($obj);
        $method = $reflection->getMethod('isIntNumeric');
        $method->setAccessible(true);
        $result = $method->invoke($obj, $input);
        self::assertSame($expected, $result);
    }

    public static function isIntNumericSet(): array
    {
        return [
            ["2", true],
            ["34.5", false],
            ["345", true],
            ["-45", true],
            ["erets", false],
            ["45w", false],
            ["56789", true],
        ];
    }

    /**
     * @dataProvider getPositionSet
     * @param int $input
     * @param int $expected
     * @covers \StringSequence\Sequencer::getPosition
     */
    public function testGetPosition(int $input, int $expected): void
    {
        $obj = new Sequencer(10);
        $reflection = new ReflectionClass($obj);
        $method = $reflection->getMethod('getPosition');
        $method->setAccessible(true);
        $result = $method->invoke($obj, $input);
        self::assertSame($expected, $result);
    }

    public static function getPositionSet(): array
    {
        return [
          [1, 1],
          [3, 3],
          [-1, 10],
          [-4, 7],
          [0, 0],
        ];
    }

    /**
     * @dataProvider getIsPeriodSet
     * @param string $input
     * @param bool $expected
     * @covers \StringSequence\Sequencer::isPeriod
     */
    public function testIsPeriod(string $input, bool $expected): void
    {
        $obj = new Sequencer(10);
        $reflection = new ReflectionClass($obj);
        $method = $reflection->getMethod('isPeriod');
        $method->setAccessible(true);
        $result = $method->invoke($obj, $input);
        self::assertSame($expected, $result);
    }

    public static function getIsPeriodSet(): array
    {
        return [
            ["1-4", true],
            ["-14--5", true],
            ["1", false],
            ["-23", false],
            ["5-*", true],
        ];
    }

    /**
     * @covers \StringSequence\Sequencer::getDefaultPeriod
     */
    public function testGetDefaultPeriod(): void
    {
        $obj = new Sequencer(10);
        $reflection = new ReflectionClass($obj);
        $method = $reflection->getMethod('getDefaultPeriod');
        $method->setAccessible(true);
        $defaultresult = $method->invoke($obj);
        self::assertSame(["start" => 1, "end" => 10, "step" => 1], $defaultresult);
        $result = $method->invoke($obj, 4);
        self::assertSame(["start" => 1, "end" => 10, "step" => 4], $result);
    }

    /**
     * @dataProvider getIsRepeaterSet
     * @param string $input
     * @param bool $expected
     * @covers \StringSequence\Sequencer::isRepeater
     */
    public function testIsRepeater(string $input, bool $expected): void
    {
        $obj = new Sequencer(10);
        $reflection = new ReflectionClass($obj);
        $method = $reflection->getMethod('isRepeater');
        $method->setAccessible(true);
        $result = $method->invoke($obj, $input);
        self::assertSame($expected, $result);
    }

    public static function getIsRepeaterSet(): array
    {
        return [
            ["*", true],
            ["*/2", true],
            ["34", false],
            ["5*", false],
            ["* / 56", true],
        ];
    }

    /**
     * @dataProvider getParseRepeaterSet
     * @param string $input
     * @param int $expected
     * @covers \StringSequence\Sequencer::parseRepeater
     */
    public function testParseRepeater(string $input, int $expected): void
    {
        $obj = new Sequencer(10);
        $reflection = new ReflectionClass($obj);
        $method = $reflection->getMethod('parseRepeater');
        $method->setAccessible(true);
        $result = $method->invoke($obj, $input);
        self::assertSame($expected, $result);
    }

    public static function getParseRepeaterSet(): array
    {
        return [
            ["*", 1],
            ["*/2", 2],
            ["* / 56", 56],
        ];
    }

    /**
     * @dataProvider getParsePeriodSet
     * @param int $length
     * @param string $input
     * @param array $expected
     * @covers \StringSequence\Sequencer::parsePeriod
     */
    public function testParsePeriod(int $length, string $input, array $expected): void
    {
        $obj = new Sequencer($length);
        $reflection = new ReflectionClass($obj);
        $method = $reflection->getMethod('parsePeriod');
        $method->setAccessible(true);
        $result = $method->invoke($obj, $input);
        self::assertSame($expected, $result);
    }

    public static function getParsePeriodSet(): array
    {
        return [
            [
                10,
                "1-10",
                ["start" => 1, "end" => 10, "step" => 1]
            ],
            [
                20,
                "3-7",
                ["start" => 3, "end" => 7, "step" => 1]
            ],
            [
                12,
                "2-*/2",
                ["start" => 2, "end" => 12, "step" => 2]
            ],
            [
                10,
                "-4-*",
                ["start" => 7, "end" => 10, "step" => 1]
            ],
            [
                10,
                "-4- 9",
                ["start" => 7, "end" => 9, "step" => 1]
            ],
            [
                10,
                "-4 - -2",
                ["start" => 7, "end" => 9, "step" => 1]
            ],
        ];
    }

    /**
     * @dataProvider getAddPeriodSet
     * @param int $length
     * @param string $input
     * @param array $expected
     * @covers \StringSequence\Sequencer::addPeriod
     */
    public function testAddPeriod(int $length, array $period, array $expected): void
    {
        $obj = new Sequencer($length);
        $reflection = new ReflectionClass($obj);
        $method = $reflection->getMethod('addPeriod');
        $method->setAccessible(true);
        $method->invoke($obj, $period);
        $result = $obj->get();
        self::assertSame($expected, $result);
    }

    public static function getAddPeriodSet(): array
    {
        return [
            [
                4,
                ["start" => 1, "end" => 2, "step" => 1],
                [1 => true, 2 => true, 3 => false, 4 => false]
            ],
            [
                5,
                ["start" => 2, "end" => 5, "step" => 2],
                [1 => false, 2 => true, 3 => false, 4 => true, 5 => false]
            ],
            [
                4,
                ["start" => 1, "end" => 4, "step" => 1],
                [1 => true, 2 => true, 3 => true, 4 => true]
            ],
            [
                5,
                ["start" => 2, "end" => 3, "step" => 4],
                [1 => false, 2 => true, 3 => false, 4 => false, 5 => false]
            ],
        ];
    }

    /**
     * @dataProvider getAddSet
     * @param int $length
     * @param string $input
     * @param array $expected
     * @covers \StringSequence\Sequencer::add
     */
    public function testAdd(int $length, string $input, array $expected): void
    {
        $obj = new Sequencer($length);
        $obj->add($input);
        $result = $obj->get();
        self::assertIsArray($result);
        self::assertCount($length, $result);
        self::assertSame($expected, $result);
    }

    public static function getAddSet(): array
    {
        return [
          [
              3,
              "1",
              [1 => true, 2 => false, 3 => false]
          ],
          [
              4,
              "2,4",
              [1 => false, 2 => true, 3 => false, 4 => true]
          ],
          [
                5,
                "2,-1, 4",
                [1 => false, 2 => true, 3 => false, 4 => true, 5 => true]
          ],
          [
                6,
                "1, 2-4, 6",
                [1 => true, 2 => true, 3 => true, 4 => true, 5 => false, 6 => true]
          ],
          [
                6,
                "3, */2, 5 -6",
                [1 => true, 2 => false, 3 => true, 4 => false, 5 => true, 6 => true]
          ],
          [
                3,
                "*",
                [1 => true, 2 => true, 3 => true]
          ],
        ];
    }

    public function testAddOutOfBoundsException(): void
    {
        $this->expectException("StringSequence\Exception\OutOfBoundsException");
        $this->expectExceptionMessage("Out of bound for index: 11. Index should be in range from 1 to 2");
        $obj = new Sequencer(2);
        $obj->add("11");
    }
}
