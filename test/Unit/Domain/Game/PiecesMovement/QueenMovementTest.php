<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use PHPUnit\Framework\TestCase;

class QueenMovementTest extends TestCase
{
    use CanMoveStraight;
    use CanMoveDiagonally;

    protected function getPieceType(): PieceType
    {
        return PieceType::Queen;
    }
}
