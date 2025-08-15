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
        return $piece !== null
            && $from != $to
            && $piece->color === $this->onTurn
            && match ($piece->type) {
                PieceType::Rook => $this->isRookMoveAllowed($from, $to, $piece),
                PieceType::Bishop => $this->isBishopMoveAllowed($from, $to, $piece),
                PieceType::Pawn => $this->isPawnMoveAllowed($from, $to, $piece),
                default => false,
            };
    }

    private function isRookMoveAllowed(Position $from, Position $to, Piece $piece): bool
    {
        return $from->isOnStraight($to) && $this->pathIsFree($from, $to, $piece);
    }

    private function isBishopMoveAllowed(Position $from, Position $to, Piece $piece): bool
    {
        return $from->isOnDiagonal($to) && $this->pathIsFree($from, $to, $piece);
    }

    private function pathIsFree(Position $from, Position $to, Piece $piece): bool
    {
        $movementVector = [
            $to->file->value <=> $from->file->value,
            $to->rank->value <=> $from->rank->value,
        ];

        $next = $from;
        do {
            $next = new Position(
                File::from($next->file->value + $movementVector[0]),
                Rank::from($next->rank->value + $movementVector[1])
            );
            $blockingPiece = $this->board->getPiece($next);

            $isNotBlocked =
                $blockingPiece === null
                || ($blockingPiece->color !== $piece->color && $next == $to);
        } while ($isNotBlocked && $next != $to);

        return $isNotBlocked;
    }

    private function isPawnMoveAllowed(Position $from, Position $to, Piece $piece): bool
    {
        $direction = $this->onTurn === Color::White ? 1 : -1;
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