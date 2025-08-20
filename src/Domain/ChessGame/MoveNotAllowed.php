<?php

namespace Mkosturkov\Chess\Domain\ChessGame;

final class MoveNotAllowed extends \DomainException
{
    public function __construct(
        public readonly Position $from,
        public readonly Position $to
    )
    {
        parent::__construct(
            "Move from {$from->file->name}{$from->rank->value} to {$to->file->name}{$to->rank->value} is not allowed"
        );
    }
}