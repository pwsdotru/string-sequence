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
}
