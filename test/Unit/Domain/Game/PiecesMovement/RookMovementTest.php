<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use PHPUnit\Framework\TestCase;

class RookMovementTest extends TestCase
{
    use CanMoveStraight;

    protected function getPieceType(): PieceType
    {
        return PieceType::Rook;
    }

    public function test_can_not_move_not_diagonally(): void
    {
        [$from, $board] = $this->setupBoard($this->getPieceType());
        $allowed = $this->getAllowedMoves($board, $from);
        $disallowed = new PositionsCollection()
            ->diff(new PositionsCollection()->straightsOf($from));

        $this->assertTrue(
            $disallowed
                ->diff($allowed)
                ->equals($disallowed)
        );
    }

}
