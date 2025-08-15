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
        $b->board[$po->file->value][$po->rank->value] = $pi;
        return $b;
    }

    public function getPiece(Position $p): ?Piece
    {
        return $this->board[$p->file->value][$p->rank->value];
    }
}