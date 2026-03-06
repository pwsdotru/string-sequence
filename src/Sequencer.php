<?php

declare(strict_types=1);

namespace StringSequence;

use StringSequence\Exception\InvalidFormatException;
use StringSequence\Exception\OutOfBoundsException;

use function sprintf;

class Sequencer
{
    private $_sequence;
    private $_length;

    /**
     * Sequencer constructor.
     * @param int $length
     */
    public function __construct(int $length = 0)
    {
        $this->_length = $length;
        $this->_sequence = [];
        for ($i = 1; $i <= $length; $i++) {
            $this->_sequence[$i] = false;
        }
    }

    public function add(string $input): self
    {
        $tokens = $this->getTokens($input);
        array_walk($tokens, [$this, 'addToken']);
        return $this;
    }

    public function get(): array
    {
        return $this->_sequence;
    }

    private function getTokens(string $input): array
    {
        if ("" === trim($input)) {
            return [];
        }
        $result = explode(',', $input);
        return array_map(
            function (string $i): string {
                return trim($i);
            },
            $result
        );
    }

    private function getPosition(int $number): int
    {
        //If negative number, then calculate for tail
        if ($number < 0) {
            $number = $this->_length + $number + 1;
        }
        return $number;
    }

    private function setSeq(int $number): void
    {
        if (
            1 <= $number && $number <= $this->_length
            && array_key_exists($number, $this->_sequence)
        ) {
            $this->_sequence[$number] = true;
            return;
        }
        throw new OutOfBoundsException(sprintf("Out of bound for index: %d. Index should be in range from 1 to %d", $number, $this->_length));
    }

    private function isIntNumeric(string $input): bool
    {
        return is_numeric($input) && $input === (string)(int)$input;
    }

    private function isPeriod(string $input): bool
    {
        return (strpos($input, "-", 1) !== false);
    }

    private function isRepeater(string $input): bool
    {
        return ($input === "*" || strpos($input, "/", 1) !== false);
    }

    private function getDefaultPeriod(int $step = 1): array
    {
        return  ["start" => 1, "end" => $this->_length, "step" =>  $step];
    }

    private function parsePeriod(string $input): array
    {
        $result = $this->getDefaultPeriod();
        $separator = strpos($input, "-", 1);
        $start = trim(substr($input, 0, $separator));
        $end = trim(substr($input, $separator + 1));
        if ($this->isIntNumeric($start)) {
            $result["start"] = $this->getPosition((int)$start);
        } else {
            throw new InvalidFormatException(sprintf("Invalid format for start of period: %s in %s", $start, $input));
        }
        if ($this->isIntNumeric($end)) {
            $result["end"] = $this->getPosition((int)$end);
        } elseif ($this->isRepeater($end)) {
            $result["step"] = $this->parseRepeater($end);
        } else {
            throw new InvalidFormatException(sprintf("Invalid format for end of period: %s in %s", $start, $input));
        }
        return $result;
    }

    private function parseRepeater(string $input): int
    {
        if ($input === "*") {
            return 1;
        }
        $separator = strpos($input, "/", 1);
        $repeater = trim(substr($input, $separator + 1));
        if ($this->isIntNumeric($repeater)) {
            return (int)$repeater;
        }
        throw new InvalidFormatException(sprintf("Invalid format for repeater string: %s in %s", $repeater, $input));
    }

    private function addPeriod(array $period): void
    {
        for ($i = $period["start"]; $i <= $period["end"]; $i += $period["step"]) {
            $this->setSeq($i);
        }
    }

    private function addToken(string $input): void
    {
        if ($this->isIntNumeric($input)) {
            $this->setSeq($this->getPosition((int)$input));
            return;
        }

        $period = null;
        if ($this->isRepeater($input)) {
            $period = $this->getDefaultPeriod($this->parseRepeater($input));
        } elseif ($this->isPeriod($input)) {
            $period = $this->parsePeriod($input);
        } else {
            throw new InvalidFormatException("Invalid format for token: %s", $input);
        }
        $this->addPeriod($period);
    }
}
