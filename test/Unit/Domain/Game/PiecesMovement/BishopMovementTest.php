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
    
    public function test_can_not_move_not_straight(): void
    {
        [$from, $board] = $this->setupBoard($this->getPieceType());
        $allowed = $this->getAllowedMoves($board, $from);
        $disallowed = new PositionsCollection()
            ->diff(new PositionsCollection()->diagonalsOf($from));

        $this->assertTrue(
            $disallowed
                ->diff($allowed)
                ->equals($disallowed)
        );;
    }

}
