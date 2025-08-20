<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\Color;
use Mkosturkov\Chess\Domain\ChessGame\Piece;
use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use Mkosturkov\Chess\Domain\ChessGame\Position;

trait LinesMovementCases
{
    use MovementTestsHelpers;

    abstract static protected function assertTrue(bool $condition, string $message = ''): void;

    abstract static protected function assertFalse(bool $condition, string $message = ''): void;

    abstract protected function getPieceType(): PieceType;


    protected function _test_can_capture(Position $target, array $positionsBehind): void
    {
        [$from, $board] = $this->setupBoard($this->getPieceType());
        $board = $board->withPiece($target, new Piece(Color::Black, PieceType::Pawn));
        $allowed = $this->getAllowedMoves($board, $from);
        $this->assertTrue($allowed->contains($target));
        foreach ($positionsBehind as $position) {
            $this->assertFalse($allowed->contains($position));
        }
    }

    protected function _test_can_not_capture_own(Position $target, array $positionsBehind): void
    {
        [$from, $board] = $this->setupBoard($this->getPieceType());
        $board = $board->withPiece($target, new Piece(Color::White, PieceType::Pawn));
        $allowed = $this->getAllowedMoves($board, $from);
        $this->assertFalse($allowed->contains($target));
        foreach ($positionsBehind as $position) {
            $this->assertFalse($allowed->contains($position));
        }
    }

    protected function _test_path_is_blocked(Position $target, array $positionsBehind): void
    {
        [$from, $board] = $this->setupBoard($this->getPieceType());
        $board = $board
            ->withPiece($target, new Piece(Color::White, PieceType::Pawn))
            ->withPiece($positionsBehind[0], new Piece(Color::Black, PieceType::Pawn));
        $this->assertFalse($this->isMoveAllowed($board, $from, $target));
    }
}