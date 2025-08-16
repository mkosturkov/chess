<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use PHPUnit\Framework\TestCase;

class BishopMovementTest extends TestCase
{
    use CanMoveDiagonally;

    protected function getPieceType(): PieceType
    {
        return PieceType::Bishop;
    }
}
