<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\File;
use Mkosturkov\Chess\Domain\ChessGame\Position;
use Mkosturkov\Chess\Domain\ChessGame\Rank;
use PHPUnit\Framework\Attributes\DataProvider;

trait CanMoveDiagonally
{
    use MovementTestsHelpers;

    public function test_can_move_diagonally(): void
    {
        [$from, $board] = $this->setupBoard($this->getPieceType());
        $allowed = $this->getAllowedMoves($board, $from);
        $expected = new PositionsCollection()->diagonalsOf($from);
        $this->assertTrue($expected->equals($allowed));
    }

    #[DataProvider('diagonalDirectionsTargetsDataProvider')]
    public function test_can_capture_diagonally(Position $target, array $positionsBehind): void
    {
        $this->_test_can_capture($target, $positionsBehind);
    }

    #[DataProvider('diagonalDirectionsTargetsDataProvider')]
    public function test_is_blocked_diagonally_by_own(Position $target, array $positionsBehind): void
    {
        $this->_test_can_not_capture_own($target, $positionsBehind);
    }


    public static function diagonalDirectionsTargetsDataProvider(): array
    {
        return [
            'diagonal up right' => [
                new Position(File::F, Rank::Six),
                [new Position(File::G, Rank::Seven), new Position(File::H, Rank::Eight)]
            ],
            'diagonal up left' => [
                new Position(File::B, Rank::Six),
                [new Position(File::A, Rank::Seven)]
            ],
            'diagonal down right' => [
                new Position(File::F, Rank::Two),
                [new Position(File::G, Rank::One)]
            ],
            'diagonal down left' => [
                new Position(File::B, Rank::Two),
                [new Position(File::A, Rank::One)]
            ]
        ];
    }

}