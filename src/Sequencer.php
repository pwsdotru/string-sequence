<?php

declare(strict_types=1);

namespace StringSequence;

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
    }

    private function isIntNumeric(string $input): bool
    {
        return is_numeric($input) && $input === (string)(int)$input;
    }

    private function addToken(string $input): void
    {
        if ($this->isIntNumeric($input)) {
            $this->setSeq($this->getPosition((int)$input));
            return;
        }
    }
}
