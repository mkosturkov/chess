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

class PawnsMovementTest extends TestCase
{
    use MovementTestsHelpers;

    protected function getPieceType(): PieceType
    {
        return PieceType::Pawn;
    }


    #[DataProvider('pawnForwardMovementProvider')]
    public function test_pawn_can_move_one_rank_forward(Color $color, Rank $targetRank)
    {
        [$from, $board] = $this->setupBoard(PieceType::Pawn, $color);
        $allowed = $this->getAllowedMoves($board, $from);

        $this->assertEquals(1, $allowed->count());
        $this->assertTrue($allowed->contains(new Position(File::D, $targetRank)));
    }

    #[DataProvider('pawnForwardMovementProvider')]
    public function test_white_pawn_can_capture(Color $color, Rank $targetRank)
    {
        [$from, $board] = $this->setupBoard(PieceType::Pawn, $color);
        $board = $board
            ->withPiece(
                new Position(File::C, $targetRank),
                new Piece($color->opposite(), PieceType::Pawn)
            )
            ->withPiece(
                new Position(File::E, $targetRank),
                new Piece($color->opposite(), PieceType::Pawn)
            );
        $allowed = $this->getAllowedMoves($board, $from);

        $this->assertEquals(3, $allowed->count());
        $this->assertTrue($allowed->contains(new Position(File::D, $targetRank)));
        $this->assertTrue($allowed->contains(new Position(File::C, $targetRank)));
        $this->assertTrue($allowed->contains(new Position(File::E, $targetRank)));
    }

    #[DataProvider('pawnForwardMovementProvider')]
    public function test_pawn_is_blocked_by_own(Color $color, Rank $targetRank)
    {
        [$from, $board] = $this->setupBoard(PieceType::Pawn, $color);
        $board = $board
            ->withPiece(
                new Position(File::D, $targetRank),
                new Piece($color, PieceType::Pawn)
            );

        $allowed = $this->getAllowedMoves($board, $from);
        $this->assertEquals(0, $allowed->count());
    }

    #[DataProvider('pawnForwardMovementProvider')]
    public function test_pawn_is_blocked_enemy(Color $color, Rank $targetRank)
    {
        [$from, $board] = $this->setupBoard(PieceType::Pawn, $color);
        $board = $board
            ->withPiece(
                new Position(File::D, $targetRank),
                new Piece($color->opposite(), PieceType::Pawn)
            );

        $allowed = $this->getAllowedMoves($board, $from);

        $this->assertEquals(0, $allowed->count());
    }

    public static function pawnForwardMovementProvider(): array
    {
        return [
            'white pawn moves forward' => [Color::White, Rank::Five],
            'black pawn moves forward' => [Color::Black, Rank::Three]
        ];
    }
}
