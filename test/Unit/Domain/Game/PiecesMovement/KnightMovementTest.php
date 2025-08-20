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

class KnightMovementTest extends TestCase
{
    use MovementTestsHelpers;

    #[DataProvider('knightMovesDataProvider')]
    public function test_can_move_to_knight_positions(Position $target): void
    {
        [$from, $board] = $this->setupBoard(PieceType::Knight);
        $this->assertTrue($this->isMoveAllowed($board, $from, $target));
    }

    #[DataProvider('knightMovesDataProvider')]
    public function test_can_capture_enemy_piece(Position $target): void
    {
        [$from, $board] = $this->setupBoard(PieceType::Knight);
        $board = $board->withPiece($target, new Piece(Color::Black, PieceType::Pawn));
        $this->assertTrue($this->isMoveAllowed($board, $from, $target));
    }

    #[DataProvider('knightMovesDataProvider')]
    public function test_cannot_capture_own_piece(Position $target): void
    {
        [$from, $board] = $this->setupBoard(PieceType::Knight);
        $board = $board->withPiece($target, new Piece(Color::White, PieceType::Pawn));
        $this->assertFalse($this->isMoveAllowed($board, $from, $target));
    }


    public static function knightMovesDataProvider(): array
    {
        return [
            'up-right L' => [new Position(File::E, Rank::Six)],
            'up-left L' => [new Position(File::C, Rank::Six)],
            'right-up L' => [new Position(File::F, Rank::Five)],
            'right-down L' => [new Position(File::F, Rank::Three)],
            'down-right L' => [new Position(File::E, Rank::Two)],
            'down-left L' => [new Position(File::C, Rank::Two)],
            'left-up L' => [new Position(File::B, Rank::Five)],
            'left-down L' => [new Position(File::B, Rank::Three)],
        ];
    }
}