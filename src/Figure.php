<?php

class Figure {
    protected $isBlack;

    /** @var array */
    protected $lastMove = [];

    public function __construct($isBlack) {
        $this->isBlack = $isBlack;
    }

    /** @noinspection PhpToStringReturnInspection */
    public function __toString() {
        throw new \Exception("Not implemented");
    }

    /**
     * @return bool
     */
    public function isBlack(): bool {
        return $this->isBlack;
    }

    public function validate($xFrom, $yFrom, $xTo, $yTo, array $figures, ?Figure $lastFigure):bool {
        return true;
    }

    public function setLastMove(array $move): void {
        $this->lastMove = $move;
    }

    public function getLastMove(): array {
        return $this->lastMove;
    }

    public function getHadMoved(): bool {
        return !empty($this->lastMove);
    }
}
