<?php

namespace Mkosturkov\Chess\Domain\ChessGame;

final class Game
{
    public function __construct(
        private Board $board
    ) {}

    public function isMoveAllowed(Position $from, Position $to): bool
    {
        $piece = $this->board->getPiece($from);

        // Can capture
        if (
            abs($from->file->value - $to->file->value) === 1
            && $this->board->getPiece($to)?->color === $piece->color->opposite()
        ) {
            return true;
        }

        // Is blocked
        if ($this->board->getPiece($to) !== null) {
            return false;
        }

        // Can move
        $direction = $piece->color == Color::White ? 1 : -1;
        return $from->file == $to->file
            && $to->rank->value - $from->rank->value === $direction;
    }

}