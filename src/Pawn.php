<?php

class Pawn extends Figure
{
    public function __toString()
    {
        return $this->isBlack ? '♟' : '♙';
    }

    /**
     * @param $xFrom
     * @param $yFrom
     * @param $xTo
     * @param $yTo
     * @param Figure[][] $figures
     * @param Figure|null $lastFigure
     * @return bool
     * @throws Exception
     */
    public function validate($xFrom, $yFrom, $xTo, $yTo, array $figures, ?Figure $lastFigure): bool
    {
        //move ahead
        if ($xFrom === $xTo) {
            if (!empty($figures[$xTo][$yTo])) {
                throw new \Exception("Wrong move: pawn destination square is not empty");
            }

            //one square move
            if (
                ($yTo - $yFrom) === ($this->isBlack ? -1 : 1)
            ) {

            } elseif (
                ($yTo - $yFrom) === ($this->isBlack ? -2 : 2)
            ) {//two square move for first move
                if ($this->getHadMoved() !== false) {
                    throw new \Exception("Wrong move: two squares move length is possible only at first time for the pawn");
                }

                $jumpOverY = $this->isBlack ? $yFrom - 1 : $yFrom + 1;
                if (isset($figures[$xTo][$jumpOverY])) {
                    throw new \Exception("Pawns may not jump over an occupied square");
                }
            } else {
                throw new \Exception("Wrong pawn move length");
            }

        } //capture figure
        elseif (
            //neighboring column - use ascii value of characters
            abs(ord($xFrom) - ord($xTo)) === 1
            &&
            //right direction
            ($yTo - $yFrom) === ($this->isBlack ? -1 : 1)
        ) {
            if (empty($figures[$xTo][$yTo])) {
                //En passant (взятие на проходе)
                if (
                    !empty($lastFigure) && get_class($lastFigure) === 'Pawn'
                    &&
                    (//first move of last figure
                        ($lastFigure->getLastMove()['yFrom'] == 2 && !$lastFigure->isBlack())
                        ||
                        ($lastFigure->getLastMove()['yFrom'] == 7 && $lastFigure->isBlack())
                    )
                    &&
                    //two square move
                    abs($lastFigure->getLastMove()['yTo'] - $lastFigure->getLastMove()['yFrom']) === 2
                    &&
                    //not own figure
                    $figures[$xFrom][$yFrom]->isBlack() !== $lastFigure->isBlack()
                    &&
                    //check "To" position
                    //moved pawn situated at the same vertical line
                    $xTo === $lastFigure->getLastMove()['xTo']
                    &&
                    $yTo - $lastFigure->getLastMove()['yTo'] === ($this->isBlack ? -1 : 1)
                ) {

                } else {
                    throw new \Exception("Wrong move: try to capture at empty square");
                }
            } else {
                if ($figures[$xFrom][$yFrom]->isBlack() === $figures[$xTo][$yTo]->isBlack()) {
                    throw new \Exception("Wrong move: try to capture your own figure");
                }
            }
        } else {
            throw new \Exception("Wrong pawn move");
        }

        return true;
    }
}
