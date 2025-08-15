<?php

namespace Mkosturkov\Chess\Domain\ChessGame;

final class Game
{
    public function __construct(
        private Board $board,
        private Color $onTurn = Color::White
    ) {}

    public function isMoveAllowed(Position $from, Position $to): bool
    {
        $piece = $this->board->getPiece($from);
        if ($piece === null || $piece->color !== $this->onTurn) {
            return false;
        }

        $direction = $piece->color === Color::White ? 1 : -1;
        $rankDiff = $to->rank->value - $from->rank->value;
        $fileDiff = abs($to->file->value - $from->file->value);

        if ($rankDiff !== $direction) {
            return false;
        }

        if ($fileDiff === 0) {
            return $this->board->getPiece($to) === null;
        }

        if ($fileDiff === 1) {
            $targetPiece = $this->board->getPiece($to);
            return $targetPiece !== null && $targetPiece->color !== $piece->color;
        }

        return false;
    }

}