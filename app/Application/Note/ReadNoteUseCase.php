<?php

namespace App\Application\Note;

use App\Domain\Note\NoteId;
use App\Domain\Note\NoteRepository;

/**
 * 1件のノートを取得するユースケース。
 */
final class ReadNoteUseCase
{
    public function __construct(private readonly NoteRepository $repository) {}

    public function handle(NoteId $id): ?NoteDto
    {
        $note = $this->repository->find($id);

        return $note === null ? null : NoteDto::fromDomain($note);
    }
}
