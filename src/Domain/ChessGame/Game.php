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
            && $this->isPieceMoveAllowed($this->board, $from, $to, $this->onTurn)
            && !$this->willBeCheckMate($from, $to);

        if (!$isMoveAllowed) {
            throw new MoveNotAllowed($from, $to);
        }
    }

    private function isPieceMoveAllowed(Board $board, Position $from, Position $to, Color $onTurn): bool
    {
        $piece = $board->getPiece($from);
        return match ($piece->type) {
            PieceType::Rook => $this->isRookMoveAllowed($board, $from, $to),
            PieceType::Bishop => $this->isBishopMoveAllowed($board, $from, $to),
            PieceType::Queen => $this->isQueenMoveAllowed($board, $from, $to),
            PieceType::Knight => $this->isKnightMoveAllowed($board, $from, $to),
            PieceType::King => $this->isKingMoveAllowed($board, $from, $to),
            PieceType::Pawn => $this->isPawnMoveAllowed($board, $from, $to, $onTurn),
            default => false,
        };
    }

    private function isRookMoveAllowed(Board $board, Position $from, Position $to): bool
    {
        return $from->isOnStraight($to) && $this->pathIsFree($board, $from, $to);
    }

    private function isBishopMoveAllowed(Board $board, Position $from, Position $to): bool
    {
        return $from->isOnDiagonal($to) && $this->pathIsFree($board, $from, $to);
    }

    private function isQueenMoveAllowed(Board $board, Position $from, Position $to): bool
    {
        return $this->isRookMoveAllowed($board, $from, $to)
            || $this->isBishopMoveAllowed($board, $from, $to);
    }

    private function pathIsFree(Board $board, Position $from, Position $to): bool
    {
        $piece = $board->getPiece($from);
        $movementVector = [
            $to->file->value <=> $from->file->value,
            $to->rank->value <=> $from->rank->value,
        ];

        $checkPath = function(Position $current) use (&$checkPath, $board, $to, $movementVector, $piece): bool {
            $next = new Position(
                File::from($current->file->value + $movementVector[0]),
                Rank::from($current->rank->value + $movementVector[1])
            );
            $blockingPiece = $board->getPiece($next);
            $endReached = $next == $to;

            $isNotBlocked = $blockingPiece === null 
                || ($blockingPiece->color !== $piece->color && $endReached);

            return $isNotBlocked && ($endReached || $checkPath($next));
        };

        return $checkPath($from);
    }

    private function isKnightMoveAllowed(Board $board, Position $from, Position $to): bool
    {
        $piece = $board->getPiece($from);
        $fileDiff = abs($to->file->value - $from->file->value);
        $rankDiff = abs($to->rank->value - $from->rank->value);

        $isLShaped = ($fileDiff === 2 && $rankDiff === 1) || ($fileDiff === 1 && $rankDiff === 2);

        if (!$isLShaped) {
            return false;
        }

        $targetPiece = $board->getPiece($to);
        return $targetPiece === null || $targetPiece->color !== $piece->color;
    }

    private function isKingMoveAllowed(Board $board, Position $from, Position $to): bool
    {
        $piece = $board->getPiece($from);
        $fileDiff = abs($to->file->value - $from->file->value);
        $rankDiff = abs($to->rank->value - $from->rank->value);

        $isOneSquareMove = $fileDiff <= 1 && $rankDiff <= 1;

        if (!$isOneSquareMove) {
            return false;
        }

        $targetPiece = $board->getPiece($to);
        return $targetPiece === null || $targetPiece->color !== $piece->color;
    }

    private function isPawnMoveAllowed(Board $board, Position $from, Position $to, Color $onTurn): bool
    {
        $piece = $board->getPiece($from);
        $direction = $onTurn === Color::White ? 1 : -1;
        $rankDiff = $to->rank->value - $from->rank->value;
        $fileDiff = abs($to->file->value - $from->file->value);

        if ($rankDiff !== $direction) {
            return false;
        }

        if ($fileDiff === 0) {
            return $board->getPiece($to) === null;
        }

        if ($fileDiff === 1) {
            $targetPiece = $board->getPiece($to);
            return $targetPiece !== null && $targetPiece->color !== $piece->color;
        }
        return false;
    }

    private function willBeCheckMate(Position $from, Position $to): bool
    {
        $testBoard = $this->board->movePiece($from, $to);
        $kingPosition = $testBoard->findKing($this->onTurn);

        if (!$kingPosition) {
            return false;
        }

        $others = $testBoard->getPositionsOfColor($this->onTurn->opposite());

        return array_any(
            $others,
            fn (Position $o) => $this->isPieceMoveAllowed(
                $testBoard,
                $o,
                $kingPosition,
                $this->onTurn->opposite()
            )
        );
    }

}