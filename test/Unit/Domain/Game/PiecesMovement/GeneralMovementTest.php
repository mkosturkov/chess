<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\Color;
use Mkosturkov\Chess\Domain\ChessGame\Piece;
use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use PHPUnit\Framework\TestCase;
use Mkosturkov\Chess\Domain\ChessGame\Board;
use Mkosturkov\Chess\Domain\ChessGame\File;
use Mkosturkov\Chess\Domain\ChessGame\Position;
use Mkosturkov\Chess\Domain\ChessGame\Rank;

class GeneralMovementTest extends TestCase
{
    use MovementTestsHelpers;

    public function test_can_not_move_empty_square()
    {
        $board = new Board();
        $from = new Position(File::A, Rank::One);
        $to = new Position(File::A, Rank::Two);
        $this->assertFalse($this->isMoveAllowed($board, $from, $to, Color::White));
    }

    public function test_can_not_move_to_source()
    {
        [$from, $board] = $this->setupBoard(PieceType::Rook);
        $this->assertFalse($this->isMoveAllowed($board, $from, $from));
    }

    public function test_can_not_move_in_an_unorthodox_way()
    {
        $this->markTestIncomplete();
    }

    public function test_can_not_move_if_going_to_lead_to_mate()
    {
        $this->markTestIncomplete();
    }

    public function test_can_not_move_anything_but_to_escape_mate()
    {
        $this->markTestIncomplete();
    }

    public function test_can_not_move_if_not_on_turn()
    {
        $board = new Board();
        $from = new Position(File::A, Rank::Two);
        $to = new Position(File::A, Rank::Three);
        $board = $board->withPiece($from, new Piece(Color::White, PieceType::Pawn));
        $this->assertFalse($this->isMoveAllowed($board, $from, $to, Color::Black));
    }
}