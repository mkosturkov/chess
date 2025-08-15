<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\Board;
use Mkosturkov\Chess\Domain\ChessGame\Color;
use Mkosturkov\Chess\Domain\ChessGame\File;
use Mkosturkov\Chess\Domain\ChessGame\Game;
use Mkosturkov\Chess\Domain\ChessGame\Piece;
use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use Mkosturkov\Chess\Domain\ChessGame\Position;
use Mkosturkov\Chess\Domain\ChessGame\PositionsCollection;
use Mkosturkov\Chess\Domain\ChessGame\Rank;

trait MovementTestsHelpers
{
    public function getAllowedMoves(Board $board, Position $from): PositionsCollection
    {
        $piece = $board->getPiece($from);
        $game = new Game($board, $piece->color);
        return new PositionsCollection()
            ->filter(fn($p) => $game->isMoveAllowed($from, $p));
    }

    public function setupBoard(Color $color, PieceType $type): array
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