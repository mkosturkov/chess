<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\Board;
use Mkosturkov\Chess\Domain\ChessGame\Color;
use Mkosturkov\Chess\Domain\ChessGame\File;
use Mkosturkov\Chess\Domain\ChessGame\Game;
use Mkosturkov\Chess\Domain\ChessGame\Piece;
use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use Mkosturkov\Chess\Domain\ChessGame\Position;
use Mkosturkov\Chess\Domain\ChessGame\Rank;

trait MovementTestsHelpers
{
    abstract static protected function assertTrue(bool $condition, string $message = ''): void;

    abstract static protected function assertFalse(bool $condition, string $message = ''): void;

    abstract protected function getPieceType(): PieceType;



    public function getAllowedMoves(Board $board, Position $from): PositionsCollection
    {
        $piece = $board->getPiece($from);
        $game = new Game($board, $piece->color);
        return new PositionsCollection()
            ->filter(fn($p) => $game->isMoveAllowed($from, $p));
    }

    /**
     * @return array{0: Position, 1: Board}
     */
    public function setupBoard(PieceType $type, Color $color = Color::White): array
    {
        $from = new Position(File::D, Rank::Four);
        $board = new Board()
            ->withPiece(
                $from,
                new Piece($color, $type)
            );
        return [$from, $board];
    }

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
        $game = new Game($board, Color::White);
        $this->assertFalse($game->isMoveAllowed($from, $target));
    }
}