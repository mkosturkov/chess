<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\Board;
use Mkosturkov\Chess\Domain\ChessGame\Color;
use Mkosturkov\Chess\Domain\ChessGame\File;
use Mkosturkov\Chess\Domain\ChessGame\Game;
use Mkosturkov\Chess\Domain\ChessGame\MoveNotAllowed;
use Mkosturkov\Chess\Domain\ChessGame\Piece;
use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use Mkosturkov\Chess\Domain\ChessGame\Position;
use Mkosturkov\Chess\Domain\ChessGame\Rank;

trait MovementTestsHelpers
{
    public function getAllowedMoves(Board $board, Position $from): PositionsCollection
    {
        return new PositionsCollection()
            ->filter(fn($p) => $this->isMoveAllowed($board, $from, $p));
    }

    public function isMoveAllowed(Board $board, Position $from, Position $to, ?Color $colorOnTurn = null): bool
    {
        $game = new Game($board, $colorOnTurn ?? $board->getPiece($from)->color);
        try {
            $game->makeMove($from, $to);
            return true;
        } catch (MoveNotAllowed $e) {
            return false;
        }
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
}