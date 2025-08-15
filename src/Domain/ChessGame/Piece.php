<?php

namespace Mkosturkov\Chess\Domain\ChessGame;

final readonly class Piece
{
    public function __construct(
        public Color     $color,
        public PieceType $type
    ) {}
}