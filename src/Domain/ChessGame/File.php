<?php

declare(strict_types=1);

namespace Mkosturkov\Chess\Domain\ChessGame;

enum File: int
{
    case A = 1;
    case B = 2;
    case C = 3;
    case D = 4;
    case E = 5;
    case F = 6;
    case G = 7;
    case H = 8;
}
