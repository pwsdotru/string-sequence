<?php

declare(strict_types=1);

namespace Tests;

use StringSequence\Sequencer;
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
}
