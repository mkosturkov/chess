<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\Color;
use Mkosturkov\Chess\Domain\ChessGame\Piece;
use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use PHPUnit\Framework\TestCase;
use Mkosturkov\Chess\Domain\ChessGame\Board;
use Mkosturkov\Chess\Domain\ChessGame\File;
use Mkosturkov\Chess\Domain\ChessGame\Game;
use Mkosturkov\Chess\Domain\ChessGame\Position;
use Mkosturkov\Chess\Domain\ChessGame\Rank;

class GeneralMovementTest extends TestCase
{
    public function test_can_not_move_empty_square()
    {
        $board = new Board();
        $game = new Game($board);
        $from = new Position(File::A, Rank::One);
        $to = new Position(File::A, Rank::Two);
        $this->assertFalse($game->isMoveAllowed($from, $to));
    }

    public function test_can_not_move_if_not_on_turn()
    {
        $board = new Board();
        $from = new Position(File::A, Rank::Two);
        $to = new Position(File::A, Rank::Three);
        $board = $board->withPiece($from, new Piece(Color::White, PieceType::Pawn));
        $game = new Game($board, Color::Black);
        $this->assertFalse($game->isMoveAllowed($from, $to));
    }
}