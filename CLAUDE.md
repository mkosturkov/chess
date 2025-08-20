# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

**Dependencies:**
- `composer install` - Install PHP dependencies

**Testing:**
- `vendor/bin/phpunit` - Run all tests
- `vendor/bin/phpunit test/Unit/Domain/Game/PiecesMovement/QueenMovementTest.php` - Run specific test file
- `vendor/bin/phpunit --filter testMethodName` - Run specific test method

## Architecture Overview

This is a PHP chess game implementation using Domain-Driven Design principles. The codebase follows a clean architecture with a clear domain model.

**Core Domain Structure:**
- **Namespace:** `Mkosturkov\Chess\Domain\ChessGame`
- **Game Logic:** `Game.php` - Central game orchestrator that validates moves and manages game state
- **Board Representation:** `Board.php` - Immutable board state with piece placement
- **Value Objects:** `Position`, `Piece`, `Color`, `PieceType`, `File`, `Rank` - Immutable domain primitives

**Movement System:**
The game uses a match expression in `Game::makeMove()` to delegate piece movement validation:
- Rook: Straight line movement (`isRookMoveAllowed`)
- Bishop: Diagonal movement (`isBishopMoveAllowed`) 
- Queen: Combined rook + bishop movement (`isQueenMoveAllowed`)
- Pawn: Forward movement with diagonal captures (`isPawnMoveAllowed`)

**Path Validation:**
`pathIsFree()` method uses movement vectors to check for blocking pieces along the path, allowing captures of opponent pieces at the destination.

**Test Architecture:**
- Tests use `MovementTestsHelpers` trait with `setupBoard()` method for consistent test setups
- `PositionsCollection` class provides helper methods for testing valid move sets
- Tests validate move legality by catching `MoveNotAllowed` exceptions

**Key Design Patterns:**
- Immutable value objects throughout the domain
- Builder pattern for board construction (`withPiece()`)
- Exception-based flow control for invalid moves