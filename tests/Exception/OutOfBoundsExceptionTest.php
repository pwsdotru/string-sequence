<?php

declare(strict_types=1);

namespace Tests;

use StringSequence\Sequencer;
use PHPUnit\Framework\TestCase;

class OutOfBoundsExceptionTest extends TestCase
{
    public function testContructDefault(): void
    {
        $this->expectException("StringSequence\Exception\OutOfBoundsException");
        $obj = new Sequencer(10);
        $obj->add("11");
    }
}
