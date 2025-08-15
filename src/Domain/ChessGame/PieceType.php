<?php

declare(strict_types=1);

namespace Mkosturkov\Chess\Domain\ChessGame;

enum PieceType: string
{
    case King = 'K';
    case Queen = 'Q';
    case Rook = 'R';
    case Bishop = 'B';
    case Knight = 'N';
    case Pawn = 'P';
}
