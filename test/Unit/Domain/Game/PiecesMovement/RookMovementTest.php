<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use PHPUnit\Framework\TestCase;

class RookMovementTest extends TestCase
{
    use CanMoveStraightCases;

    protected function getPieceType(): PieceType
    {
        return PieceType::Rook;
    }
}
