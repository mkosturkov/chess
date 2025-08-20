<?php

namespace Mkosturkov\Chess\Domain\ChessGame;

final class Game
{
    public function __construct(
        private Board $board,
        private Color $onTurn = Color::White
    ) {}

    public function makeMove(Position $from, Position $to): void
    {
        $piece = $this->board->getPiece($from);
        $isMoveAllowed = $piece !== null
            && $from != $to
            && $piece->color === $this->onTurn
            && match ($piece->type) {
                PieceType::Rook => $this->isRookMoveAllowed($from, $to, $piece),
                PieceType::Bishop => $this->isBishopMoveAllowed($from, $to, $piece),
                PieceType::Queen => $this->isQueenMoveAllowed($from, $to, $piece),
                PieceType::Knight => $this->isKnightMoveAllowed($from, $to, $piece),
                PieceType::Pawn => $this->isPawnMoveAllowed($from, $to, $piece),
                default => false,
            };

        if (!$isMoveAllowed) {
            throw new MoveNotAllowed($from, $to);
        }
    }

    private function isRookMoveAllowed(Position $from, Position $to, Piece $piece): bool
    {
        return $from->isOnStraight($to) && $this->pathIsFree($from, $to, $piece);
    }

    private function isBishopMoveAllowed(Position $from, Position $to, Piece $piece): bool
    {
        return $from->isOnDiagonal($to) && $this->pathIsFree($from, $to, $piece);
    }

    private function isQueenMoveAllowed(Position $from, Position $to, Piece $piece): bool
    {
        return $this->isRookMoveAllowed($from, $to, $piece)
            || $this->isBishopMoveAllowed($from, $to, $piece);
    }

    private function pathIsFree(Position $from, Position $to, Piece $piece): bool
    {
        $movementVector = [
            $to->file->value <=> $from->file->value,
            $to->rank->value <=> $from->rank->value,
        ];

        $checkPath = function(Position $current) use (&$checkPath, $to, $movementVector, $piece): bool {
            $next = new Position(
                File::from($current->file->value + $movementVector[0]),
                Rank::from($current->rank->value + $movementVector[1])
            );
            $blockingPiece = $this->board->getPiece($next);

            $isNotBlocked = $blockingPiece === null 
                || ($blockingPiece->color !== $piece->color && $next == $to);

            return $isNotBlocked && ($next == $to || $checkPath($next));
        };

        return $checkPath($from);
    }

    private function isKnightMoveAllowed(Position $from, Position $to, Piece $piece): bool
    {
        $fileDiff = abs($to->file->value - $from->file->value);
        $rankDiff = abs($to->rank->value - $from->rank->value);

        $isLShaped = ($fileDiff === 2 && $rankDiff === 1) || ($fileDiff === 1 && $rankDiff === 2);

        if (!$isLShaped) {
            return false;
        }

        $targetPiece = $this->board->getPiece($to);
        return $targetPiece === null || $targetPiece->color !== $piece->color;
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