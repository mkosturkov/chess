<?php

namespace Mkosturkov\Chess\Domain\ChessGame;

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
        return array_any($this->positions, fn ($i) => $i == $p);
    }

}