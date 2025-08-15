<?php

namespace Mkosturkov\Chess\Domain\ChessGame;

readonly final class Position
{
    public function __construct(
        public File $file,
        public Rank $rank
    ) {}
}