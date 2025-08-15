<?php

namespace Mkosturkov\Chess\Domain\ChessGame;

readonly final class Position
{
    public function __construct(
        public File $file,
        public Rank $rank
    ) {}

    public function isOnDiagonal(Position $other): bool
    {
        return abs($this->file->value - $other->file->value)
            === abs($this->rank->value - $other->rank->value);
    }

    public function isOnStraight(Position $other): bool
    {
        return $this->rank->value === $other->rank->value
            || $this->file->value === $other->file->value;
    }
}