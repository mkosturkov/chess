<?php

namespace Mkosturkov\Chess\Test\Domain\Game\PiecesMovement;

use Mkosturkov\Chess\Domain\ChessGame\File;
use Mkosturkov\Chess\Domain\ChessGame\Position;
use Mkosturkov\Chess\Domain\ChessGame\Rank;

final class PositionsCollection
{
    /** @var array<Position> */
    private array $positions = [];

    public function __construct()
    {
        foreach (File::cases() as $file) {
            foreach (Rank::cases() as $rank) {
                $this->positions[] = new Position($file, $rank);
            }
        }
    }

    public function count(): int
    {
        return count($this->positions);
    }

    public function filter(callable $callback): self
    {
        $c = clone $this;
        $c->positions = array_filter($this->positions, $callback);
        return $c;
    }

    public function contains(Position $p): bool
    {
        return array_any($this->positions, fn($i) => $i == $p);
    }

    public function diagonalsOf(Position $p): self
    {
        return $this->filter(fn($i) => $i->isOnDiagonal($p));
    }

    public function straightsOf(Position $p): self
    {
        return $this->filter(fn($i) => $i->isOnStraight($p));
    }

    public function diff(PositionsCollection $other): self
    {
        return $this->filter(fn($i) => !$other->contains($i));
    }

    public function equals(PositionsCollection $other): bool
    {
        return $this->diff($other)->count() === 0;
    }





}