<?php

declare(strict_types=1);

namespace Mkosturkov\Chess\Domain\ChessGame;

enum Rank: int
{
    case One = 1;
    case Two = 2;
    case Three = 3;
    case Four = 4;
    case Five = 5;
    case Six = 6;
    case Seven = 7;
    case Eight = 8;
}
