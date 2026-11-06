<?php

namespace App\Domain\Note;

use RuntimeException;

/**
 * 指定されたノートが存在しない場合の例外。
 */
final class NoteNotFoundException extends RuntimeException
{
    public function __construct(NoteId $id)
    {
        parent::__construct("Note not found: {$id->value()}");
    }
}
