<?php

declare(strict_types=1);

namespace unit;

use StringSequence\Exception;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testContructDefault(): void
    {
        $this->expectException("StringSequence\Exception");
        throw new Exception("test");
    }
}
