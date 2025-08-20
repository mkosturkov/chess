<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\Color;
use Mkosturkov\Chess\Domain\ChessGame\File;
use Mkosturkov\Chess\Domain\ChessGame\Piece;
use Mkosturkov\Chess\Domain\ChessGame\PieceType;
use Mkosturkov\Chess\Domain\ChessGame\Position;
use Mkosturkov\Chess\Domain\ChessGame\Rank;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class KingMovementTest extends TestCase
{
    use MovementTestsHelpers;

    #[DataProvider('kingMovesDataProvider')]
    public function test_can_move_to_king_positions(Position $target): void
    {
        [$from, $board] = $this->setupBoard(PieceType::King);
        $this->assertTrue($this->isMoveAllowed($board, $from, $target));
    }

    #[DataProvider('kingMovesDataProvider')]
    public function test_can_capture_enemy_piece(Position $target): void
    {
        [$from, $board] = $this->setupBoard(PieceType::King);
        $board = $board->withPiece($target, new Piece(Color::Black, PieceType::Pawn));
        $this->assertTrue($this->isMoveAllowed($board, $from, $target));
    }

    #[DataProvider('kingMovesDataProvider')]
    public function test_cannot_capture_own_piece(Position $target): void
    {
        [$from, $board] = $this->setupBoard(PieceType::King);
        $board = $board->withPiece($target, new Piece(Color::White, PieceType::Pawn));
        $this->assertFalse($this->isMoveAllowed($board, $from, $target));
    }

    public static function kingMovesDataProvider(): array
    {
        return [
            'up' => [new Position(File::D, Rank::Five)],
            'up-right' => [new Position(File::E, Rank::Five)],
            'right' => [new Position(File::E, Rank::Four)],
            'down-right' => [new Position(File::E, Rank::Three)],
            'down' => [new Position(File::D, Rank::Three)],
            'down-left' => [new Position(File::C, Rank::Three)],
            'left' => [new Position(File::C, Rank::Four)],
            'up-left' => [new Position(File::C, Rank::Five)],
        ];
    }
}