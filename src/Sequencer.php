<?php declare(strict_types=1);

namespace StringSequence;

class Sequencer {

    private $_sequence;
    private $_length;
    public function __construct(int $length = 0) {
        $this->_length = $length;
        $this->_sequence = [];
        for($i = 1; $i <= $length; $i++) {
            $this->_sequence[$i] = false;
        }
    }

    public function get(): array {
        return $this->_sequence;
    }
}
