<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\File;
use Mkosturkov\Chess\Domain\ChessGame\Position;
use Mkosturkov\Chess\Domain\ChessGame\Rank;
use PHPUnit\Framework\Attributes\DataProvider;

trait CanMoveStraight
{
    use MovementTestsHelpers;

    public function test_can_move_straight(): void
    {
        [$from, $board] = $this->setupBoard($this->getPieceType());
        $allowed = $this->getAllowedMoves($board, $from);
        $expected = new PositionsCollection()->straightsOf($from);
        $this->assertTrue($expected->equals($allowed));
    }

    #[DataProvider('horizontalDirectionsTargetsDataProvider')]
    public function test_can_capture_straight(Position $target, array $positionsBehind): void
    {
        $this->_test_can_capture($target, $positionsBehind);
    }

    #[DataProvider('horizontalDirectionsTargetsDataProvider')]
    public function test_can_not_capture_own_straight(Position $target, array $positionsBehind): void
    {
        $this->_test_can_not_capture_own($target, $positionsBehind);
    }

    #[DataProvider('horizontalDirectionsTargetsDataProvider')]
    public function test_path_is_blocked_straight(Position $target, array $positionsBehind): void
    {
        $this->_test_path_is_blocked($target, $positionsBehind);
    }

    public static function horizontalDirectionsTargetsDataProvider(): array
    {
        return [
            'horizontal right' => [
                new Position(File::F, Rank::Four),
                [new Position(File::G, Rank::Four), new Position(File::H, Rank::Four)]
            ],
            'horizontal left' => [
                new Position(File::B, Rank::Four),
                [new Position(File::A, Rank::Four)]
            ],
            'vertical up' => [
                new Position(File::D, Rank::Six),
                [new Position(File::D, Rank::Seven), new Position(File::D, Rank::Eight)]
            ],
            'vertical down' => [
                new Position(File::D, Rank::Two),
                [new Position(File::D, Rank::One)]
            ]
        ];
    }

}