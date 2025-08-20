<?php

namespace Mkosturkov\Chess\Domain\ChessGame;

final class Board
{
    /** @var array<File, array<Rank, Piece|null>> */
    private array $board;

    public function __construct()
    {
        foreach (File::cases() as $f) {
             $this->board[$f->value] = [];
             foreach (Rank::cases() as $r) {
                 $this->board[$f->value][$r->value] = null;
             }
         }
    }

    public function withPiece(Position $po, Piece $pi): self
    {
        $b = clone $this;
        $b->setPiece($po, $pi);
        return $b;
    }

    public function movePiece(Position $from, Position $to): self
    {
        $b = clone $this;
        $b->setPiece($to, $b->getPiece($from));
        $b->setPiece($from, null);
        return $b;
    }

    /** @return Position[] */
    public function getPositionsOfColor(Color $color): array
    {
        $positions = [];
        $this->walkBoard(function (Position $p) use (&$positions, $color) {
            if ($this->getPiece($p)?->color === $color) {
                $positions[] = $p;
            }
        });
        return $positions;
    }

    public function findKing(Color $color): ?Position
    {
        $kingPosition = null;
        $this->walkBoard(function (Position $p) use (&$kingPosition, $color) {
            $piece = $this->getPiece($p);
            if ($piece?->type === PieceType::King && $piece?->color === $color) {
                $kingPosition = $p;
            }
        });
        return $kingPosition;
    }

    public function getPiece(Position $p): ?Piece
    {
        return $this->board[$p->file->value][$p->rank->value];
    }

    private function walkBoard(callable $cb): void
    {
        foreach ($this->board as $file => $ranks) {
            foreach ($ranks as $rank => $piece) {
                $p = new Position(File::from($file), Rank::from($rank));
                $cb($p);
            }
        }
    }

    private function setPiece(Position $po, ?Piece $pi): void
    {
        $this->board[$po->file->value][$po->rank->value] = $pi;
    }
}