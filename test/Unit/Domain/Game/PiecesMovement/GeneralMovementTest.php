<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\Color;
use Mkosturkov\Chess\Domain\ChessGame\Piece;
use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use PHPUnit\Framework\Attributes\DataProvider;
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

    public function test_can_not_move_if_not_on_turn()
    {
        $board = new Board();
        $from = new Position(File::A, Rank::Two);
        $to = new Position(File::A, Rank::Three);
        $board = $board->withPiece($from, new Piece(Color::White, PieceType::Pawn));
        $this->assertFalse($this->isMoveAllowed($board, $from, $to, Color::Black));
    }

    #[DataProvider('mateMovesProvider')]
    public function test_can_not_move_if_not_escaping_chess($from, $to)
    {
        $board = new Board()
            ->withPiece(new Position(File::D, Rank::Four), new Piece(Color::White, PieceType::King))
            ->withPiece(new Position(File::C, Rank::Six), new Piece(Color::White, PieceType::Rook))
            // a black bishop is in front of a white king and diagonal of white rook
            ->withPiece(new Position(File::D, Rank::Five), new Piece(Color::Black, PieceType::Bishop))
            // the black pawn is next to the black bishop and in front of the white rook, giving chess to the white king
            ->withPiece(new Position(File::C, Rank::Five), new Piece(Color::Black, PieceType::Pawn));

        $this->assertFalse($this->isMoveAllowed($board, $from, $to));
    }

    public static function mateMovesProvider(): array
    {
        return [
            'king moves left (mate by bishop)' => [new Position(File::D, Rank::Four), new Position(File::C, Rank::Four)],
            'rook moves right (mate by pawn)' => [new Position(File::C, Rank::Six), new Position(File::D, Rank::Six)],
        ];
    }
}